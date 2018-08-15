#from numpy import array
from __future__ import division
def data(dataParam, fcf_backward, sol_vol, iteration, sol_lvl):

    import pyomo.environ as pyomo
    from pyomo.opt import SolverFactory
    from pyomo.core import Suffix
    import copy
    import math
    from pyomo.opt import SolverManagerFactory
    from utils.parameters import cutsback, pyomogates
    from utils.parameters import pyomoset, pyomohydro, pyomobuses
    from utils import solver
    import progressbar
    import pickle
    #import timeit
    #from objbrowser import browse
    #from pyomo.util.timing import report_timing
    #report_timing()
    
    # parallelization
    if dataParam.pl is False:
        from utils.solvermodule import backseq as solver_module
    else:
        from utils.solvermodule import backpar as solver_module

    if dataParam.sc is True:
        from utils.objcalculation import objst as obj_calc
    else:
        from utils.objcalculation import objdet as obj_calc

    # stimate progress
    bar = progressbar.ProgressBar(maxval = dataParam.st*(iteration+1), \
    widgets=[progressbar.Bar('=', '[', ']'), 'Backward stage - iteration '+str(iteration+1)+' ',
             progressbar.Percentage()])
    bar.start()
    count = 0

    # import data
    dict_hydro = pickle.load(open("savedata/hydro_save.p", "rb"))
    dict_batt = pickle.load(open("savedata/batt_save.p", "rb"))
    dict_lines = pickle.load(open("savedata/lines_save.p", "rb"))
    dict_wind = pickle.load(open("savedata/wind_hat_0.p", "rb"))
    dict_format = pickle.load(open("savedata/format_save.p", "rb"))
    dict_data = pickle.load(open("savedata/data_save_iter.p", "rb"))
    dict_wenergy = pickle.load(open("savedata/windspeed_save.p", "rb"))

    # data from dictionaries
    numBlocks = dict_format['numBlocks']
    numAreas = dict_data['numAreas']
    blocksdata = dict_data['blocksData']
    windPlants = dict_data['windPlants']
    thermalplants = dict_data['thermalPlants']
    smallplants = dict_data['smallPlants']
    hydroPlants = dict_data['hydroPlants']
    batteries = dict_data['batteries']
    demandArea = dict_format['demandArea']
    RnwArea = dict_wenergy['RnwArea']
    
    circuits = dict_data['linesData']
    fcircuits = list(range(1, len(circuits)+1))
    
    # Define solver
    opt = solver.gurobi_solver(SolverFactory)
    # Define abstract model
    model = pyomo.ConcreteModel()
    
    # SETS
    # set of demand blocks
    model.Blocks = pyomo.Set(initialize= list(range(1, numBlocks+1)))
    # set of state/cut
    model.Cuts = pyomo.Set(initialize= [1])
    # set of hydroelectric plants / reservoirs / Chains
    model.Hydro = pyomo.Set(initialize= hydroPlants)
    # set of hydro plants with reservoirs
    setData = pyomohydro(hydroPlants, dict_data['hydroReservoir'])
    model.resHydro = pyomo.Set(initialize=setData)
    # generation chains: set of spill and turbining arcs
    setData = pyomoset(dict_hydro['S-downstream'])
    model.SpillArcs = pyomo.Set(initialize= setData, dimen=2)
    setData = pyomoset(dict_hydro['T-downstream'])
    model.TurbiningArcs = pyomo.Set(initialize= setData, dimen=2)
    # set of thermal plants
    model.Thermal = pyomo.Set(initialize= thermalplants)
    # set of small plants
    model.Small = pyomo.Set(initialize= smallplants)
    # set of wind farms
    model.Wind = pyomo.Set(initialize= windPlants)
    # set of battery units
    model.Batteries = pyomo.Set(initialize= batteries)
    # set of areas in the system
    model.Areas = pyomo.Set(initialize= list(range(1, numAreas+1)))
    model.AreasDmd = pyomo.Set(initialize= demandArea)
    model.AreasRnw = pyomo.Set(initialize= RnwArea)
    # set of lines in the system
    model.Circuits = pyomo.Set(initialize= fcircuits)
    setData = pyomobuses(circuits,'in')
    model.linesAreaIn = pyomo.Set(initialize=setData, dimen=2)
    setData = pyomobuses(circuits,'out')
    model.linesAreaOut = pyomo.Set(initialize=setData, dimen=2)
 
    # PARAMETERS
    # cost of thermal production
    model.cost = pyomo.Param(model.Thermal, mutable=True)
    # identification of thermal plants by areas
    dictplant = {thermalplants[z]: dict_format['area_thermal'][z] for z in range(len(thermalplants))}
    model.ThermalArea = pyomo.Param(model.Thermal, initialize=dictplant)
    # identification of small plants by areas
    dictplant = {smallplants[z]: dict_format['area_small'][z] for z in range(len(smallplants))}
    model.SmallArea = pyomo.Param(model.Small, initialize=dictplant)
    # cost of energy rationing
    model.rationing = pyomo.Param(model.Areas, model.Blocks, mutable=True)
    # demand for each stage
    model.demand = pyomo.Param(model.Areas, model.Blocks, mutable=True)
    # inflows for each stage
    model.inflows = pyomo.Param(model.Hydro, mutable=True)
    # wind inflows for each stage
    model.meanWind = pyomo.Param(model.Areas, model.Blocks, mutable=True)
    # production factor for each hydro plant
    model.factorH = pyomo.Param(model.Hydro, mutable=True)
    # production cost (CxC) for each hydro plant
    dictplant = {hydroPlants[z]: dict_hydro['oymcost'][z] for z in range(len(hydroPlants))}
    model.hydroCost = pyomo.Param(model.Hydro, initialize=dictplant)
    # identification of hydro plants by areas
    dictplant = {hydroPlants[z]: dict_format['area_hydro'][z] for z in range(len(hydroPlants))}
    model.HydroArea = pyomo.Param(model.Hydro, initialize=dictplant)
    # production factor for each battery unit
    model.factorB = pyomo.Param(model.Batteries, mutable=True)
    # identification of batteries by areas
    dictplant = {batteries[z]: dict_batt['b_area'][z] for z in range(len(batteries))}
    model.BatteriesArea = pyomo.Param(model.Batteries, initialize=dictplant)
    # state volume by stage
    model.stateVol = pyomo.Param(model.Hydro, mutable=True)
    # state level of batteriy
    model.stateLvl = pyomo.Param(model.Batteries, mutable=True)
    model.stateLvlBlk = pyomo.Param(model.Batteries, model.Blocks, mutable=True)
    # coeficcient of lineal segments in the future cost function
    model.coefcTerm = pyomo.Param(model.Hydro, model.Cuts, mutable=True)
    # constant term of lineal segments in the future cost function
    model.constTerm = pyomo.Param(model.Cuts, mutable=True)
    # coeficcient of lineal segments in the future cost function
    model.coefcBatt = pyomo.Param(model.Batteries, model.Cuts, mutable=True)
    
    # BOUNDS
    # bounds (min and max) on hydro generation
    model.maxGenH = pyomo.Param(model.Hydro, model.Blocks, mutable=True)
    # bounds (min and max) on thermal generation
    model.minGenT = pyomo.Param(model.Thermal, model.Blocks, mutable=True)
    model.maxGenT = pyomo.Param(model.Thermal, model.Blocks, mutable=True)
    # bounds (min and max) on small generation
    model.minGenS = pyomo.Param(model.Small, model.Blocks, mutable=True)
    model.maxGenS = pyomo.Param(model.Small, model.Blocks, mutable=True)
    # bounds (min and max) on batteries generation
    model.maxGenB = pyomo.Param(model.Batteries, model.Blocks, mutable=True)
    # bounds (min and max) on wind area generation
    model.maxGenW = pyomo.Param(model.Areas, model.Blocks, mutable=True)
    # bounds (min and max) on capacity of reservoirs
    dictplant = {hydroPlants[z]: dict_hydro['volmin'][z] for z in range(len(hydroPlants))}
    model.minVolH = pyomo.Param(model.Hydro, initialize=dictplant)
    dictplant = {hydroPlants[z]: dict_hydro['volmax'][z] for z in range(len(hydroPlants))}
    model.maxVolH = pyomo.Param(model.Hydro, initialize=dictplant)
    # bounds (min and max) on capacity of batteries
    model.minlvlB = pyomo.Param(model.Batteries, mutable=True)
    model.maxlvlB = pyomo.Param(model.Batteries, mutable=True)
    # bounds (max) on capacity of lines
    model.lineLimit = pyomo.Param(model.Circuits, model.Blocks, mutable=True)

    # reservoirs limits
    def boundVolH(model, h):
        return (model.minVolH[h], model.maxVolH[h])
    # batteries storage limits
    def boundlvlB(model, r):
        return (model.minlvlB[r], model.maxlvlB[r])
    def boundlvlBlock(model, r, b):
        return (0, model.maxlvlB[r])
    # thermal production
    def boundProdT(model, t, b):
        return (model.minGenT[t, b], model.maxGenT[t, b])
    # thermal production
    def boundProdS(model, m, b):
        return (model.minGenS[m, b], model.maxGenS[m, b])
    # hydro production
    def boundProdH(model, h, b):
        return (0, model.maxGenH[h, b])
    # batteries production
    def boundProdB(model, r, b):
        return (0, model.maxGenB[r, b])
    # wind area production
    def boundProdW(model, a, b):
        return (0, model.maxGenW[a, b])
    # lines limits
    def boundLines(model, l, b):
        return (0, model.lineLimit[l, b])
    # lines limits
    def boundDeficit(model, a, b):
        return (0, model.demand[a, b])
    
    # DECISION VARIABLES
    # thermal production
    model.prodT = pyomo.Var(model.Thermal, model.Blocks, bounds=boundProdT)
    # small production
    model.prodS = pyomo.Var(model.Small, model.Blocks, bounds=boundProdS)
    # hydro production
    model.prodH = pyomo.Var(model.Hydro, model.Blocks, bounds=boundProdH)
    # thermal production
    model.prodW = pyomo.Var(model.AreasRnw, model.Blocks, bounds=boundProdW)
    # Battery production
    model.prodB = pyomo.Var(model.Batteries, model.Blocks, bounds=boundProdB)
    # battery charge
    model.chargeB = pyomo.Var(model.Batteries, model.Blocks, bounds=boundProdB)
    # lines transfer limits
    model.line = pyomo.Var(model.Circuits, model.Blocks, bounds=boundLines)
    # spilled outflow of hydro plant
    model.spillH = pyomo.Var(model.Hydro, model.Blocks, domain=pyomo.NonNegativeReals)
    # energy non supplied
    model.deficit = pyomo.Var(model.Areas, model.Blocks, bounds=boundDeficit)
    # energy in nodes - balance
    model.balance = pyomo.Var(model.Areas, model.Blocks)
    # final volume
    model.vol = pyomo.Var(model.Hydro, bounds=boundVolH)
    # final battery level
    model.lvl = pyomo.Var(model.Batteries, bounds=boundlvlB)
    # future cost funtion value
    model.futureCost = pyomo.Var(domain=pyomo.NonNegativeReals)
    # spilled outflow of hydro plant
    model.spillW = pyomo.Var(model.Areas, model.Blocks, domain=pyomo.NonNegativeReals)
    # limit of storage at each block
    model.lvlBlk = pyomo.Var(model.Batteries, model.Blocks, bounds=boundlvlBlock)

    # conditional constraints
    if dataParam.pf is True:
        dict_intensity = pickle.load(open("savedata/matrixbeta_save.p", "rb"))
        
        # generation chains: set of spill and turbining arcs
        setData = pyomoset(dict_intensity['matrixLineBus'][0])
        model.linebus = pyomo.Set(initialize= setData, dimen=2)
        
        # lines intensities OPF
        model.flines = pyomo.Param(model.Circuits, model.Areas, mutable=True)
        
    # flowgates parameters
    if dataParam.gt is True:
        
        numGates = dict_format['numGates']
        gatesets = dict_lines['gatesets']
        gateslimit = dict_lines['gateslimit']
    
        # set of gates flow in the system
        model.Gates = pyomo.Set(initialize= list(range(1, numGates+1)))
    
        # flow gates set
        setData = pyomogates(gatesets)
        model.gateLines = pyomo.Set(initialize=setData, dimen=3)
    
        # defining limit of gate flows
        model.gateLimt = pyomo.Param(model.Gates, model.Blocks, mutable=True)
        
    # OBJ FUNCTION
    # total cost of thermal production
    def obj_expr(model):
        return (sum((model.cost[t] * model.prodT[t, b]) for t in model.Thermal for b in model.Blocks) +
                sum((model.hydroCost[h] * model.prodH[h, b]* model.factorH[h]) for h in model.Hydro for b in model.Blocks) +
                sum((model.rationing[area, b] * model.deficit[area, b]) for area in model.AreasDmd for b in model.Blocks) +
                model.futureCost)
    # Objective function
    model.OBJ = pyomo.Objective(rule=obj_expr)
    
    # CONSTRAINTS
    # define constraint: demand must be served in each block and stage
    def ctDemand(model, area, b):
        return (sum(model.prodT[t, b] for t in model.Thermal if model.ThermalArea[t] == area) +
                sum(model.prodS[m, b] for m in model.Small if model.SmallArea[m] == area) +
                sum(model.prodH[h, b]*model.factorH[h] for h in model.Hydro if model.HydroArea[h] == area) +
                sum(model.prodB[r, b] for r in model.Batteries if model.BatteriesArea[r] == area) +
                sum(model.prodW[a, b] for a in model.AreasRnw if a == area ) + 
                model.balance[area,b] + model.deficit[area, b] == model.demand[area, b])
    # add constraint to model according to indices
    model.ctDemand = pyomo.Constraint(model.Areas, model.Blocks, rule=ctDemand)

    # ebergy balace in nodes
    def ctBalance(model, area, b):
        return (sum(model.line[l, b] for l in model.Circuits if (l,area) in model.linesAreaOut) - 
                sum(model.line[l, b] for l in model.Circuits if (l,area) in model.linesAreaIn) ==
                model.balance[area,b])
    # add constraint
    model.ctBalace = pyomo.Constraint(model.Areas, model.Blocks, rule=ctBalance)
        
    # define constraint: volume conservation
    def ctVol(model, h):
        return (model.stateVol[h] + model.inflows[h] -
                sum(model.prodH[h, b] for b in model.Blocks) -
                sum(model.spillH[h, b] for b in model.Blocks) +
                sum(sum(model.prodH[hup, b] for b in model.Blocks) for hup in model.Hydro if (hup, h) in model.TurbiningArcs) +
                sum(sum(model.spillH[sup, b] for b in model.Blocks) for sup in model.Hydro if (sup, h) in model.SpillArcs) == 
                model.vol[h])
    # add constraint to model according to indices
    model.ctVol = pyomo.Constraint(model.Hydro, rule=ctVol)

    # energy conservation by block
    def ctLvlBlk(model, r, b):
        return (model.stateLvlBlk[r, b] + model.chargeB[r, b]*model.factorB[r] -
                model.prodB[r, b]/model.factorB[r] == model.lvlBlk[r, b])
    # add constraint to model according to indices
    model.ctLvlBlk = pyomo.Constraint(model.Batteries, model.Blocks, rule=ctLvlBlk)

    # energy conservation by stage
    def ctLvl(model, r):
        return (model.stateLvl[r] +
                sum(model.chargeB[r, b]*model.factorB[r] for b in model.Blocks) -
                sum(model.prodB[r, b]/(model.factorB[r]) for b in model.Blocks) == model.lvl[r])
    # add constraint to model according to indices
    model.ctLvl = pyomo.Constraint(model.Batteries, rule=ctLvl)

    # define constraint: Wind production conservation
    def ctGenW(model, area, b):
        return (sum(model.chargeB[r, b] for r in model.Batteries if model.BatteriesArea[r] == area) +
                model.spillW[area, b] + model.prodW[area, b] == model.meanWind[area, b])
    # add constraint to model according to indices
    model.ctGenW = pyomo.Constraint(model.AreasRnw, model.Blocks, rule=ctGenW)

    # define constraint: future cost funtion
    def ctFcf(model, c):
        return (sum((model.coefcTerm[h, c] * model.vol[h]) for h in model.resHydro) +
                sum((model.coefcBatt[r, c] * model.lvl[r]) for r in model.Batteries) +
                model.constTerm[c] <= model.futureCost)
    # add constraint to model according to indices
    model.ctFcf = pyomo.Constraint(model.Cuts, rule=ctFcf)

    # flowgates constraints
    if dataParam.gt is True:
        # define gate constraints
        def ctGates(model, gate, b):
            return( -model.gateLimt[gate,b] <=
                    sum(sum(model.balance[area,b] * model.flines[l, area] for area in model.Areas if (l, area) in model.linebus) 
                            for (gt,l,area) in model.gateLines if gt == gate) <= 
                    model.gateLimt[gate,b])
        # add constraint to model according to indices
        model.ctGates = pyomo.Constraint(model.Gates, model.Blocks, rule=ctGates)
        
    if dataParam.pf is True:
        # define opf constraints
        def ctOpf(model, ct, b):
            return( sum(model.balance[area,b] *  
                    model.flines[ct, area] for area in model.Areas if (ct, area) in model.linebus) == 
                    model.line[ct, b])
        # add constraint to model according to indices
        model.ctOpf = pyomo.Constraint(model.Circuits, model.Blocks, rule=ctOpf)
    
    # Creating instance
    model.dual = Suffix(direction=Suffix.IMPORT)

    ############################### Backward analysis #########################

    int_conf = (1-dataParam.er)*dataParam.sb
    int_bound = math.ceil(int_conf)

    # Loop analysis
    for i in range(dataParam.st, 0, -1): # debug - stages

        # opf matrix restrictions
        for z in range(len(circuits)):
            for y in range(numBlocks):
                model.lineLimit[z+1, y+1] = dict_lines['l_limits'][i-1][circuits[z][0]-1][circuits[z][1]-1]*blocksdata[0][y]
        
        if dataParam.pf is True:
            # network modifications
            for z in range(len(circuits)):
                for area1 in range(numAreas):
                    model.flines[z+1, area1+1] = dict_intensity['matrixbeta'][i-1][z][area1]

        # flowgates constraints
        if dataParam.gt is True:
            for gate1 in range(numGates):
                for y in range(numBlocks):
                    model.gateLimt[gate1+1, y+1] = gateslimit[gate1][i-1]*blocksdata[0][y]
            
        # update the local fcf
        model.Cuts.clear()
        for z in range(len(fcf_backward[i])):
            model.Cuts.add(z+1)
            model.constTerm[z+1] = fcf_backward[i][z][2]
            for y, plant in enumerate(batteries):
                model.coefcBatt[plant, z+1] = fcf_backward[i][z][1][y]
            for y, plant in enumerate(hydroPlants):
                model.coefcTerm[plant, z+1] = fcf_backward[i][z][0][y]
        model.ctFcf.reconstruct()

        # update rationing cost and demand values by stage
        for area1 in range(numAreas):
            for y in range(numBlocks):
                model.rationing[area1+1, y+1] = dict_data['rationingData'][0][i-1]
                model.demand[area1+1, y+1] = dict_format['demand'][area1][i-1][y]
                model.maxGenW[area1+1, y+1] = dict_wind['hat_area'][area1][i-1]*blocksdata[0][y]

        # define cuts for states simulation
        cuts_iter, cuts_iter_B = cutsback(i, dict_data, sol_vol, iteration, sol_lvl, dict_batt)

        for z, plant in enumerate(hydroPlants):
            model.factorH[plant] = dict_hydro['prodFactor'][z][i-1]
            for y in range(numBlocks):
                model.maxGenH[plant, y+1] = dict_hydro['u_limit'][z][i-1]*blocksdata[0][y]

        for z, plant in enumerate(batteries):
            model.minlvlB[plant] = dict_batt["b_storage"][z][i-1][1]
            model.maxlvlB[plant] = dict_batt["b_storage"][z][i-1][0]
            model.factorB[plant] = dict_data['battData'][4][z]
            for y in range(numBlocks):
                model.maxGenB[plant, y+1] = dict_batt["b_limit"][z][i-1]*blocksdata[0][y]*blocksdata[2][y]

        # update thermal generation cost by stage
        for z, plant in enumerate(thermalplants):
            model.cost[plant] = dict_format['opCost'][z][i-1]
            for y in range(numBlocks):
                model.minGenT[plant, y+1] = dict_format['thermalMin'][i-1][z]*blocksdata[0][y]
                model.maxGenT[plant, y+1] = dict_format['thermalMax'][i-1][z]*blocksdata[0][y]

        for z, plant in enumerate(smallplants):
            for y in range(numBlocks):
                model.minGenS[plant, y+1] = 0
                model.maxGenS[plant, y+1] = dict_format['smallMax'][i-1][z]*blocksdata[0][y]
        
        # loop - hyperplanes
        feasible_cuts = []
        for j in range(len(cuts_iter[0])):

            # initial condition for coefficient phi and constant delta
            phi_delta = [[0]*len(hydroPlants), [0]*len(batteries), 0]

            for z, plant in enumerate(hydroPlants):
                model.stateVol[plant] = cuts_iter[z][j]
            for z, plant in enumerate(batteries):
                model.stateLvl[plant] = cuts_iter_B[z][j]
                for y in range(numBlocks):
                    model.stateLvlBlk[plant,y+1] = cuts_iter_B[z][j]/numBlocks
            
            # Solver module (Single core or parallel)
            objective_list, duals_batt, duals, total_obj = solver_module(dataParam.sb, i,
            dict_data,dict_format,model,opt,SolverFactory,SolverManagerFactory,dict_wenergy)
            
            # progress analysis
            bar.update(count+1); count += 1

            #print(total_obj)
            delta_cut, phi_risk, phi_batt_risk = obj_calc(objective_list,int_bound,dataParam.sb,
            dataParam.cm,dataParam.er,hydroPlants,batteries,duals,duals_batt,total_obj)

            # save phi and delta for the future cost function
            delta_cut_2 = sum([phi_risk[p]*cuts_iter[p][j] for p in range(len(phi_risk))])
            delta_cut_2_batt = sum([phi_batt_risk[p]*cuts_iter_B[p][j] for p in range(len(phi_batt_risk))])
            # Results for next iteration
            delta = delta_cut - delta_cut_2_batt - delta_cut_2
            #print(delta)

            # last phi_delta
            for z in range(len(hydroPlants)): phi_delta[0][z] = phi_risk[z]
            for z in range(len(batteries)): phi_delta[1][z] = phi_batt_risk[z]
            phi_delta[2] = delta

            feasible_cuts.append(phi_delta)

        #######################################################################

        fcf_backward[i-1]=copy.deepcopy(feasible_cuts)

    return fcf_backward
