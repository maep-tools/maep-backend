def data(read_data,file):
    
    if read_data is True:

        #Reading system information
        from scripts.readData import data_file
        print('Reading data...')
        data_file('datasystem/'+str(file)+'.xlsx')
        # delete temporal files

def data_consistency(stages, seriesBack, seriesForw):
    
    import pickle
    from utils.paramvalidation import paramlimits
    
    # Load the dictionary back from the pickle file
    dict_data = pickle.load(open("savedata/data_save.p", "rb"))
    # No of maximum number of stages
    noStages = len(dict_data['horizon'])
    
    # validating input parameters
    paramlimits(stages, seriesBack, seriesForw, noStages)

def parameters(param_calculation,sensDem,stages,eps_area,eps_all):
    
    import pickle

    # Load the dictionary back from the pickle file
    dict_data = pickle.load(open("savedata/data_save.p", "rb"))
    
    # calculation options SI or NO
    if param_calculation is True:
        print('Parameters calculation ...')
    
        # Creating fixed input files '''
        from utils.input_data import inputdata
        inputdata(dict_data, sensDem)
        from utils.input_hydro import inputhydro
        inputhydro(dict_data)
    
        from utils.input_wind import inputwindSet, inputInflowWind
        inputwindSet(dict_data, stages, 1, 0)
        inputInflowWind(dict_data, stages, eps_area, eps_all)
    
        from utils.input_others import inputbatteries, inputlines
        inputbatteries(dict_data, stages)
        inputlines(dict_data, stages)

def grid(param_opf, stages):
    
    # opf restrictions
    if param_opf is True:
        print('Parameters opf ...')
    
        from utils.opf_data import ybus
        ybus(stages)

def wmodel2(wind_model2, stages, seriesBack):
    
    import pickle

    # Load the dictionary back from the pickle file
    dict_data = pickle.load(open("savedata/data_save.p", "rb"))
    
    # Including wind power plants with model 2
    if wind_model2 is True:
        print('Model of wind power plants with losses ...')
    
        from utils.readWind import historical10m, format10m
        historical10m()
        format10m()
    
        from utils.input_wind import inputwindSet #, inputInflowWind, energyRealWind
        inputwindSet(dict_data, stages, 0, 1)
        # factores = energyRealWind(dict_data, seriesBack, stages)
        
def optimization(stages, seriesBack, eps_risk, commit, parallel, param_opf, max_iter, results,
                 policy, simulation, seriesForw, extra_stages, curves, gates, params):
    
    import pickle
    from scripts import forward
    from scripts import backward
    from scripts import optimality
    from utils import classes
    import sys, json
    import redis
    r = redis.StrictRedis(host='localhost', port=6379, db=0)



    # Load the dictionary back from the pickle file
    dict_data = pickle.load(open("savedata/data_save.p", "rb"))
    
    # dictionaries
    batteries = dict_data['batteries']
    hydroPlants = dict_data['hydroPlants']
    dict_batt = pickle.load(open("savedata/batt_save.p", "rb"))
    
    # Iteration inf _ improve the states under evaluation
    sol_vol = [[] for x in range(stages+1)] # Hydro iteration
    sol_lvl = [[] for x in range(stages+1)] # Batteries iteration
    sol_vol[0].append(dict_data['volData'][0])
    sol_lvl[0].append([dict_data['battData'][0][x]*dict_batt["b_storage"][x][0][0] for x in range(len(batteries))])
    
    iteration = 0 # Counter for number of iterations
    confidence = 0 # stop criteria
    
    # Save operational cost by iteration
    operative_cost = [[], []]
    
    # define: parameter classes
    # deterministic / stochastic analysis
    stochastic = True
    if seriesBack == 1: stochastic = False # Deterministic

    dataParamB = classes.param_B(stages, seriesBack, stochastic, eps_risk, commit, parallel, param_opf, gates)
    dataParamF = classes.param_F(stages, seriesForw, max_iter, results, param_opf, gates)
    
    # export options SI or NO
    if results is True:
        from utils.saveresults import printresults
        
    #==============================================================================
    # loop iterations
    if policy is True:
    
        while not iteration >= max_iter and not confidence >= 2:
    
            # save the FCF - backward steps
            fcf_backward = [[] for x in range(stages+1)]
            fcf_backward[stages]=[[[0]*len(hydroPlants), [0]*len(batteries), 0]]
    
            # Backward_Risk6_par to parallel simulation
            fcf_backward = backward.data(dataParamB, fcf_backward, sol_vol, iteration, sol_lvl)
            
            data = {}
            data['state'] = 'loopIterations'
            data['message'] = 'Iterando backward ' + str(iteration)
            data['phase'] = 3
            data['percent'] = ((iteration + 1) / max_iter) * 50
            json_data = json.dumps(data)
            r.publish('room/' +str(params['id']), json_data)
            percent = str(data['percent']) + '% Backward'
            print(percent)

            # Forward stage - Pyomo module
            (sol_vol, sol_lvl, sol_costs, sol_scn) = forward.data(confidence, fcf_backward,
            dataParamF, iteration, sol_vol, sol_lvl)

            data = {}
            data['state'] = 'loopIterations'
            data['message'] = 'Iterando Forward ' + str(iteration)
            data['phase'] = 3
            data['percent'] = ((iteration + 1) / max_iter) * 100
            percent = str(data['percent']) + '% Forward'
            print(percent)

            json_data = json.dumps(data)
            r.publish('room/' +str(params['id']), json_data)

            # confidence
            confd, op_cost, op_inf = optimality.data(sol_costs, seriesForw)
            confidence += confd
            iteration += 1
    
            # Saver results
            operative_cost[0].append(op_cost), operative_cost[1].append(op_inf)
    
            if iteration == max_iter or confidence == 2:
                datafcf = {"fcf_backward":fcf_backward, "sol_vol":sol_vol, "sol_lvl":sol_lvl, 'sol_scn':sol_scn}
                pickle.dump(datafcf, open("savedata/fcfdata.p", "wb"))
    
                # generate report
                if results is True:
                    print('Writing results ...')
                    # results files and reports
                    printresults(seriesForw, (stages-extra_stages), sol_scn, curves)
    
    elif policy is False and simulation is True:
    
        # stages to be simulate
        dict_fcf = pickle.load(open("savedata/fcfdata.p", "rb"))
        fcf_backward = dict_fcf['fcf_backward']
    
        sol_vol = dict_fcf['sol_vol']
        sol_lvl = dict_fcf['sol_lvl']
        iteration = 0
        confidence = 1
    
        # Forward stage - Pyomo module
        (sol_vol, sol_lvl, sol_costs, sol_scn) = forward.data(confidence, fcf_backward,
        dataParamF, iteration, sol_vol, sol_lvl)
    
        # Saver results
        confidence, op_cost, op_inf = optimality.data(sol_costs,seriesForw)
        operative_cost[0].append(op_cost), operative_cost[1].append(op_inf)
    
        # generate report
        if results is True:
            print('Writing results ...')
            # results files and reports
            printresults(seriesForw, (stages-extra_stages), sol_scn, curves)
    
    elif policy is False and simulation is False:
    
        if results is True:
    
            # recover results
            dict_fcf = pickle.load(open("savedata/fcfdata.p", "rb"))
            sol_scn = dict_fcf['sol_scn']
    
            print('Writing results ...')
            # results files and reports
            printresults(seriesForw, (stages-extra_stages), sol_scn, curves)
        