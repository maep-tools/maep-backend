# forward stage
from __future__ import division
def data(confidence, fcf_backward, dataParam, iteration, sol_vol, sol_lvl):

    #import InputFilesForw
    import pyomo.environ as pyomo
    from pyomo.opt import SolverFactory
    from pyomo.core import Suffix
    from utils.parameters import pyomoformat
    from utils.saveresults import saveiter
    from utils import solver
    import progressbar
    import pickle

    # progress analysis
    bar = progressbar.ProgressBar(maxval = dataParam.sf*dataParam.st, \
    widgets=[progressbar.Bar('=', '[', ']'), ' Forward stage - iteration '+str(iteration+1)+' ', 
             progressbar.Percentage()])
    bar.start(); count = 0
    
    # import data
    dict_hydro = pickle.load(open("savedata/hydro_save.p", "rb"))
    dict_batt = pickle.load(open("savedata/batt_save.p", "rb"))
    dict_lines = pickle.load(open("savedata/lines_save.p", "rb"))
    dict_windenergy = pickle.load(open("savedata/windspeed_save.p", "rb"))
    dict_wind = pickle.load(open("savedata/wind_hat_0.p", "rb"))
    dict_format = pickle.load(open("savedata/format_save.p", "rb"))
    dict_data = pickle.load(open("savedata/data_save_iter.p", "rb"))

    # data from dictionaries
    numAreas = dict_data['numAreas']
    linesData = dict_data['linesData']
    blocksData = dict_data['blocksData']
    windPlants = dict_data['windPlants']
    thermalPlants = dict_data['thermalPlants']
    smallPlants = dict_data['smallPlants']
    batteries = dict_data['batteries']
    hydroPlants = dict_data['hydroPlants']

    # print results
    lenblk = range(dict_format['numBlocks'])
    lenstg = range(dataParam.st); lensc = range(dataParam.sf)

    genThermal = [[[[[] for bl in lenblk] for z in thermalPlants] for x in lenstg] for y in lensc]
    genSmall = [[[[[] for bl in lenblk] for z in smallPlants] for x in lenstg] for y in lensc]
    genHydro = [[[[[] for bl in lenblk] for z in hydroPlants] for x in lenstg] for y in lensc]
    genwind = [[[[[] for bl in lenblk] for z in range(numAreas)] for x in lenstg] for y in lensc]
    spillwind = [[[[[] for bl in lenblk] for z in range(numAreas)] for x in lenstg] for y in lensc]
    genBatteries = [[[[[] for bl in lenblk] for z in batteries] for x in lenstg] for y in lensc]
    genDeficit = [[[[[] for bl in lenblk] for x in lenstg] for y in lensc] for x in range(numAreas)]
    loadBatteries = [[[[[] for bl in lenblk] for z in batteries] for x in lenstg] for y in lensc]
    lvlBatteries = [[[[] for z in batteries] for x in lenstg] for y in lensc]
    lvlHydro = [[[[] for z in hydroPlants] for x in lenstg] for y in lensc]
    spillHydro = [[[[[] for bl in lenblk] for z in hydroPlants] for x in lenstg] for y in lensc]
    linTransfer = [[[[[] for a in range(numAreas)] for z in range(numAreas)] for x in lenstg] for y in lensc]

    # Define solver
    opt = solver.gurobi_solver(SolverFactory)
    # Define abstract model
    model = pyomo.ConcreteModel()

    # SETS
    # set of demand blocks
    model.Blocks = pyomo.Set(initialize=list(range(1, dict_format['numBlocks']+1)))
    # set of state/cut
    model.Cuts = pyomo.Set(initialize=[1])
    # set of hydroelectric plants / reservoirs
    model.Hydro = pyomo.Set(initialize=hydroPlants)
    # set of thermal plants
    model.Thermal = pyomo.Set(initialize=thermalPlants)
    # set of thermal plants
    model.Small = pyomo.Set(initialize=smallPlants)
    # set of wind farms
    model.Wind = pyomo.Set(initialize=windPlants)
    # set of battery units
    model.Batteries = pyomo.Set(initialize=batteries)
    # set of areas in the system
    model.Areas = pyomo.Set(initialize=list(range(1, numAreas+1)))

    # PARAMETERS
    # cost of thermal production
    model.cost = pyomo.Param(model.Thermal, mutable=True)
    # Thermal plants by area
    dictplant = {thermalPlants[z]: dict_format['area_thermal'][z] for z in range(len(thermalPlants))}
    model.ThermalArea = pyomo.Param(model.Thermal, initialize=dictplant)
    # Small plants by area
    dictplant = {smallPlants[z]: dict_format['area_small'][z] for z in range(len(smallPlants))}
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
    # Hydro plants by area
    dictplant = {hydroPlants[z]: dict_format['area_hydro'][z] for z in range(len(hydroPlants))}
    model.HydroArea = pyomo.Param(model.Hydro, initialize=dictplant)
    # hydro plants with reservoirs
    dictplant = {hydroPlants[z]: dict_data['hydroReservoir'][z] for z in range(len(hydroPlants))}
    model.resHydro = pyomo.Param(model.Hydro, initialize=dictplant)
    # production cost (CxC) for each hydro plant
    dictplant = {hydroPlants[z]: dict_hydro['oymcost'][z] for z in range(len(hydroPlants))}
    model.hydroCost = pyomo.Param(model.Hydro, initialize=dictplant)
    # production factor for each battery unit
    model.factorB = pyomo.Param(model.Batteries, mutable=True)
    # Batteries by area
    dictplant = {batteries[z]: dict_batt['b_area'][z] for z in range(len(batteries))}
    model.BatteriesArea = pyomo.Param(model.Batteries, initialize=dictplant)
    # coeficcient of lineal segments in the future cost function
    model.coefcTerm = pyomo.Param(model.Hydro, model.Cuts, mutable=True)
    # constant term of lineal segments in the future cost function
    model.constTerm = pyomo.Param(model.Cuts, mutable=True)
    # coeficcient of lineal segments in the future cost function
    model.coefcBatt = pyomo.Param(model.Batteries, model.Cuts, mutable=True)
    # Initial volume in reservoirs
    model.iniVol = pyomo.Param(model.Hydro, mutable=True)
    # Initial storage in batteries
    model.iniLvl = pyomo.Param(model.Batteries, mutable=True)
    model.iniLvlBlk = pyomo.Param(model.Batteries, model.Blocks, mutable=True)
    # Hydro chains parameters
    paramData = pyomoformat(hydroPlants, hydroPlants,dict_hydro['T-downstream'])
    model.TurbiningArcs = pyomo.Param(model.Hydro, model.Hydro, initialize=paramData)
    # generation chains
    paramData = pyomoformat(hydroPlants, hydroPlants,dict_hydro['S-downstream'])
    model.SpillArcs = pyomo.Param(model.Hydro, model.Hydro, initialize=paramData)

    # BOUNDS
    # bounds (min and max) on hydro generation
    #model.minGenH = pyomo.Param(model.Hydro, model.Blocks, mutable=True)
    model.maxGenH = pyomo.Param(model.Hydro, model.Blocks, mutable=True)
    # bounds (min and max) on thermal generation
    model.minGenT = pyomo.Param(model.Thermal, model.Blocks, mutable=True)
    model.maxGenT = pyomo.Param(model.Thermal, model.Blocks, mutable=True)
    # bounds (min and max) on thermal generation
    model.minGenS = pyomo.Param(model.Small, model.Blocks, mutable=True)
    model.maxGenS = pyomo.Param(model.Small, model.Blocks, mutable=True)
    # bounds (min and max) on batteries generation
    #model.minGenB = pyomo.Param(model.Batteries, model.Blocks, mutable=True)
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
    model.lineLimit = pyomo.Param(model.Areas, model.Areas, model.Blocks, mutable=True)

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
    # small production
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
    def boundLines(model, ai, af, b):
        return (0,model.lineLimit[ai, af, b])
   
    # DECISION VARIABLES
    # thermal production
    model.prodT = pyomo.Var(model.Thermal, model.Blocks, bounds=boundProdT)
    # small production
    model.prodS = pyomo.Var(model.Small, model.Blocks, bounds=boundProdS)
    # hydro production
    model.prodH = pyomo.Var(model.Hydro, model.Blocks, bounds=boundProdH)
    # wind power production
    model.prodW = pyomo.Var(model.Areas, model.Blocks, bounds=boundProdW)
    # Battery production
    model.prodB = pyomo.Var(model.Batteries, model.Blocks, bounds=boundProdB)
    # battery charge
    model.chargeB = pyomo.Var(model.Batteries, model.Blocks, bounds=boundProdB)
    # lines transfer limits
    model.line = pyomo.Var(model.Areas, model.Areas, model.Blocks, bounds=boundLines)
    # spilled outflow of hydro plant
    model.spillH = pyomo.Var(model.Hydro, model.Blocks, domain=pyomo.NonNegativeReals)
    # energy non supplied
    model.deficit = pyomo.Var(model.Areas, model.Blocks, domain=pyomo.NonNegativeReals)
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

    if dataParam.pf is True:
        dict_intensity = pickle.load(open("savedata/matrixbeta_save.p", "rb"))
        circuits = dict_data['linesData']
        fcircuits = list(range(1,len(circuits)+1))

        # set of circuits in the system
        model.Circuits = pyomo.Set(initialize = fcircuits)
        # lines intensities OPF
        model.flines = pyomo.Param(model.Circuits, model.Areas, mutable=True)

        # identification of circuits by areas
        dictcircuit = {fcircuits[z]: circuits[z][0] for z in range(len(circuits))}
        model.flinesAreaIn = pyomo.Param(model.Circuits, initialize=dictcircuit)
        dictcircuit = {fcircuits[z]: circuits[z][1] for z in range(len(circuits))}
        model.flinesAreaOut = pyomo.Param(model.Circuits, initialize=dictcircuit)

    # OBJ FUNCTION
    # total cost of thermal production
    def obj_expr(model):
        return (sum((model.cost[t] * model.prodT[t,b]) for t in model.Thermal for b in model.Blocks) +
                sum((model.hydroCost[h] * model.prodH[h,b] * model.factorH[h]) for h in model.Hydro for b in model.Blocks) +
                sum((model.rationing[area, b] * model.deficit[area, b]) for area in model.Areas for b in model.Blocks) +
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
                sum((model.line[a,area, b]-model.line[area,a, b]) for a in model.Areas if a is not area) +
                model.prodW[area, b] + model.deficit[area, b] == model.demand[area, b])
    # add constraint to model according to indices
    model.ctDemand = pyomo.Constraint(model.Areas, model.Blocks, rule=ctDemand)

    # define constraint: volume conservation
    def ctVol(model, h) :
        return (model.iniVol[h] + model.inflows[h] -
                sum(model.prodH[h, b] for b in model.Blocks) -
                sum(model.spillH[h, b] for b in model.Blocks) +
                sum(sum(model.prodH[hup, b] for b in model.Blocks) for hup in model.Hydro if model.TurbiningArcs[hup, h] == 1) +
                sum(sum(model.spillH[sup, b] for b in model.Blocks) for sup in model.Hydro if model.SpillArcs[sup, h] == 1) ==
                model.vol[h] )
    # add constraint to model according to indices
    model.ctVol = pyomo.Constraint(model.Hydro, rule=ctVol)

    # energy conservation by block
    def ctLvlBlk(model, r, b):
        return (model.iniLvlBlk[r, b] + model.chargeB[r, b]*model.factorB[r] -
                model.prodB[r, b]/model.factorB[r] == model.lvlBlk[r, b])
    # add constraint to model according to indices
    model.ctLvlBlk = pyomo.Constraint(model.Batteries, model.Blocks, rule=ctLvlBlk)

    # energy conservation by stage
    def ctLvl(model, r):
        return (model.iniLvl[r] +
                sum(model.chargeB[r, b]*model.factorB[r] for b in model.Blocks) -
                sum(model.prodB[r, b]/(model.factorB[r]) for b in model.Blocks) == model.lvl[r])
    # add constraint to model according to indices
    model.ctLvl = pyomo.Constraint(model.Batteries, rule=ctLvl)

    # define constraint: Wind production conservation
    def ctGenW(model, area, b) :
        return (sum( model.chargeB[r, b] for r in model.Batteries if model.BatteriesArea[r] == area) +
                model.spillW[area, b] + model.prodW[area, b] == model.meanWind[area, b])
    # add constraint to model according to indices
    model.ctGenW = pyomo.Constraint(model.Areas, model.Blocks, rule=ctGenW)

    # define constraint: future cost funtion
    def ctFcf(model, c) :
        return (sum((model.coefcTerm[h, c] * model.vol[h]) for h in model.Hydro if model.resHydro[h] == 1) +
                sum((model.coefcBatt[r, c] * model.lvl[r]) for r in model.Batteries) +
                model.constTerm[c]  <= model.futureCost)
    # add constraint to model according to indices
    model.ctFcf = pyomo.Constraint(model.Cuts, rule=ctFcf)

    if dataParam.pf is True:
        # define opf constraints
        def ctOpf(model, ct, b):
            return(sum(model.flines[ct, area] * 
                   sum(model.line[a, area, b] for a in model.Areas if a is not area) for area in model.Areas) <=
                   model.lineLimit[model.flinesAreaIn[ct],model.flinesAreaOut[ct], b])
        # add constraint to model according to indices
        model.ctOpf = pyomo.Constraint(model.Circuits, model.Blocks, rule=ctOpf)

    # Creating instance
    model.dual = Suffix(direction=Suffix.IMPORT)

    ################ Forward analysis #############################

    sol_cost_scn = [[],[]] # Save operation csot and cost-to-go value

    # Save results
    sol_vol_iter = [[0 for x in hydroPlants] for x in lenstg] # Hydro iteration
    sol_lvl_batt = [[0 for x in batteries] for x in lenstg] # Batteries iteration

    # Save new state for the current iteration
    sol_scn = [[] for x in lensc] # Hydro iteration
    marg_costs = [[[] for x in lenstg] for x in lensc] # Hydro iteration

    # Create a model instance and optimize
    for k in lensc: # Iteration by scenarios

        # Update the initial volume at each stage
        for i, plant in enumerate(hydroPlants): 
            model.iniVol[plant] = dict_data['volData'][0][i]
        for i, plant in enumerate(batteries): 
            model.iniLvl[plant] = dict_data['battData'][0][i]*dict_batt["b_storage"][i][0][0]
            for y in lenblk:
                model.iniLvlBlk[plant, y+1] = dict_data['battData'][0][i]*dict_batt["b_storage"][i][0][0]/dict_format['numBlocks']

        sol_cost = [[],[]] # Save operation cost and cost-to-go value

        for s in lenstg: # Iteration by stages

            # opf matriz restrictions
            if dataParam.pf is True:
                for z in range(len(circuits)):
                    for area1 in range(numAreas):
                        restrc = abs(dict_intensity['matrixbeta'][s][z][area1])
                        if restrc < 1e-6: restrc = 0
                        model.flines[z+1,area1+1]= restrc

            InflowsHydro = []
            InflowsHydro += [dict_format['inflow_hydro'][n][1][s][k] for n in range(len(hydroPlants))]

            for z, plant in enumerate(hydroPlants):
                model.factorH[plant] = dict_hydro['prodFactor'][z][s]
                model.inflows[plant] = InflowsHydro[z]
                for y in lenblk:
                    model.maxGenH[plant, y+1] = dict_hydro['u_limit'][z][s]*blocksData[0][y]

            for z in lenblk:
                for y in range(numAreas):
                    model.meanWind[y+1,z+1] = dict_windenergy['windenergy_area'][y][s][k][z]
                    model.demand[y+1,z+1] = dict_format['demand'][y][s][z]
                    model.rationing[y+1,z+1] = dict_data['rationingData'][0][s]
                    model.maxGenW[y+1,z+1] = dict_wind['hat_area'][y][s]*blocksData[0][z]

            # Update the generation cost
            for i, plant in enumerate(thermalPlants):
                model.cost[plant] = dict_format['opCost'][i][s]
                for y in lenblk:
                    model.minGenT[plant, y+1] = dict_format['thermalMin'][s][i]*blocksData[0][y]
                    model.maxGenT[plant, y+1] = dict_format['thermalMax'][s][i]*blocksData[0][y]

            # Update small plants limits
            for i, plant in enumerate(smallPlants):
                for y in lenblk:
                    model.minGenS[plant, y+1] = 0
                    model.maxGenS[plant, y+1] = dict_format['smallMax'][s][i]*blocksData[0][y]

            # Update batteries limits
            for i, plant in enumerate(batteries):
                model.minlvlB[plant] = dict_batt["b_storage"][i][s][1]
                model.maxlvlB[plant] = dict_batt["b_storage"][i][s][0]
                model.factorB[plant] = dict_data['battData'][4][i]
                for y in lenblk:
                    model.maxGenB[plant, y+1] = dict_batt['b_limit'][i][s]*blocksData[0][y]*blocksData[2][y]

            # Update the cost-to-go function
            model.Cuts.clear()
            for z in range(len(fcf_backward[s+1])):
                model.Cuts.add(z+1)
                model.constTerm[z+1] = fcf_backward[s+1][z][2]
                for y, plant in enumerate(batteries):
                    model.coefcBatt[plant, z+1] = fcf_backward[s+1][z][1][y]
                for y, plant in enumerate(hydroPlants):
                    model.coefcTerm[plant, z+1] = fcf_backward[s+1][z][0][y]
            model.ctFcf.reconstruct()

            # interconnection limits
            for area1 in range(numAreas):
                for area2 in range(numAreas):
                    for y in lenblk:
                        model.lineLimit[area1+1,area2+1,y+1] = dict_lines['l_limits'][s][area1][area2]*blocksData[0][y]

            # solver
            opt.solve(model)#, tee=True)
            #with open('pyomo_model.txt', 'w') as f:
            #    model.pprint(ostream=f)
            # instance.display()

            # objective function value
            sol_objective = model.OBJ(); costtogo = model.futureCost()
            sol_cost[0].append(sol_objective-costtogo); sol_cost[1].append(sol_objective)
            sol_scn[k].append(sol_objective-costtogo)
            #print(sol_objective)

            vol_f_stage = [] # Save results of initial volume - v_t+1
            for vol_fin in [model.vol]:
                varobject = getattr(model, str(vol_fin))
                vol_f_stage += [varobject[i].value for i in hydroPlants]

            lvl_f_stage = [] # Save results of initial level - l_t+1
            for lvl_fin in [model.lvl]:
                varobject = getattr(model, str(lvl_fin))
                lvl_f_stage += [varobject[i].value for i in batteries]

            # Svael volt_t+1 for the next backward iteration
            sol_vol_iter[s] = [sum(x) for x in zip(sol_vol_iter[s], vol_f_stage)]
            sol_lvl_batt[s] = [sum(x) for x in zip(sol_lvl_batt[s], lvl_f_stage )]

            # Update the initial volume at each stage
            for i, plant in enumerate(hydroPlants):
                model.iniVol[plant] = vol_f_stage[i]
            for i, plant in enumerate(batteries): 
                model.iniLvl[plant] = lvl_f_stage[i]
                for y in lenblk:
                    model.iniLvlBlk[plant,y+1] = lvl_f_stage[i]/dict_format['numBlocks']

            # marginal cost of each area
            if (iteration + 1 == dataParam.mi or confidence == 1):
                duals_dmd = [[[] for x in lenblk] for y in range(numAreas)]
                d_object = getattr(model, 'ctDemand')
                for areadem in range(numAreas):
                    for i in lenblk:
                        duals_dmd[areadem][i].append(model.dual[d_object[areadem+1,i+1]])
                marg_costs[k][s] = duals_dmd

            # RESULTS
            if ((iteration + 1 == dataParam.mi or confidence == 1) and dataParam.er is True):

                (genThermal,genHydro,genBatteries,genDeficit,loadBatteries,lvlBatteries,
                lvlHydro,linTransfer,spillHydro,genwind,spillwind,genSmall) = saveiter(k,
                s,lenblk,thermalPlants,model,model.prodT,genThermal,model.prodH,model.prodB,
                model.deficit,model.chargeB,model.lvl,model.vol,hydroPlants,batteries,
                genHydro,genBatteries,loadBatteries,lvlBatteries,lvlHydro,genDeficit,
                numAreas,linTransfer,model.line,linesData,spillHydro,model.spillH,genwind,
                model.prodW,model.spillW,spillwind,genSmall,smallPlants,model.prodS)

            # progress of the analysis
            bar.update(count+1)
            count += 1

        # Operation costs by scenarios
        sol_cost_scn[0].append(sum(sol_cost[0]))
        sol_cost_scn[1].append(sol_cost[1][0])

    for s in lenstg:
        # save the last solutions
        last_sol = [x/dataParam.sf for x in sol_vol_iter[s]]
        last_batt = [x/dataParam.sf for x in sol_lvl_batt[s]]
        if last_sol not in sol_vol[s+1] or last_batt not in sol_lvl[s+1]:
            sol_vol[s+1].append([x/dataParam.sf for x in sol_vol_iter[s]])
            sol_lvl[s+1].append([x/dataParam.sf for x in sol_lvl_batt[s]])

    # Export results
    if ((iteration + 1 == dataParam.mi or confidence == 1) and dataParam.er is True):

        # export data
        DataDictionary = { "genThermal":genThermal,"genHydro":genHydro,"genSmall":genSmall,
        "genBatteries":genBatteries,"genDeficit":genDeficit,"loadBatteries":loadBatteries,
        "lvlBatteries":lvlBatteries,"lvlHydro":lvlHydro,"linTransfer":linTransfer,
        "spillHydro":spillHydro,"genwind":genwind,"spillwind":spillwind,"marg_costs":marg_costs}

        pickle.dump(DataDictionary, open( "savedata/results_save.p", "wb" ) )

    return (sol_vol, sol_lvl, sol_cost_scn, sol_scn)

