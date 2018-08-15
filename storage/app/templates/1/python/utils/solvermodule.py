
def backseq(scenarios,i,dict_data,dict_format,model,opt,none1,none2,dict_windenergy):

    #from objbrowser import browse

    hydroPlants = dict_data['hydroPlants']
    batteries = dict_data['batteries']
    volData = dict_data['volData']

    # save data
    objective_list = []; total_obj = 0
    duals_batt = [[] for x in range(len(batteries))]
    duals = [[] for x in range(len(hydroPlants))]

    for k in range(scenarios):

        # Modifying input file: coefficient phi and constant delta; initial state; storage unit limits

        InflowsHydro = []
        InflowsHydro += [dict_format['inflow_hydro'][n][1][i-1][k] for n in range(len(hydroPlants))]

        for z in range(len(hydroPlants)):
            model.inflows[hydroPlants[z]] = InflowsHydro[z]
        for z in range(dict_data['numAreas']):
            for y in range(dict_format['numBlocks']):
                model.meanWind[z+1,y+1] = dict_windenergy['windenergy_area'][z][i-1][k][y]

        # Reconstruct the instance and solve
        #model.ctVol.reconstruct(); model.ctGenW.reconstruct()

        opt.solve(model)#, tee=True)
        #instance.display()
        #with open('pyomo_model.txt', 'w') as f:
        #    model.pprint(ostream=f)
        #browse(model)

        # duals solution related to volume and storage-batteries conservation constraint
        d_object = getattr(model, 'ctVol')
        for plant in range(len(hydroPlants)):
            if (volData[2][plant] > 0 and volData[3][plant] > 0):
                duals[plant].append(model.dual[d_object[hydroPlants[plant]]])
            else:
                duals[plant].append(0)
            #print(model.dual[d_object[hydroPlants[plant]]])
            #print(d_object[hydroPlants[plant]])
        #print(duals)
        d_object = getattr(model, 'ctLvl')
        for plant in range(len(batteries)):
            duals_batt[plant].append(model.dual[d_object[batteries[plant]]])

        # objective function value
        sol_objective = model.OBJ()
        #print(sol_objective)
        objective_list.append([sol_objective,k])
        total_obj += sol_objective

    #print(duals)
    return objective_list,duals_batt,duals,total_obj

def backpar(scenarios,i,dict_data,dict_format,model,none,SolverFactory,
            SolverManagerFactory,dict_windenergy):

    import sys
    #from objbrowser import browse

    solver_manager = SolverManagerFactory('pyro')
    if solver_manager is None:
        print ("Failed to create solver manager.")
        sys.exit(1)

    hydroPlants = dict_data['hydroPlants']
    batteries = dict_data['batteries']
    numBlocks = dict_format['numBlocks']
    numAreas = dict_data['numAreas']

    # save data
    objective_list = []; total_obj = 0
    duals_batt = [[] for x in range(len(batteries))]
    duals = [[] for x in range(len(hydroPlants))]

    # loop scenarios
    action_map = dict()
    # maps action handles to instances
    with solver_manager as manager:

        opt_solver = SolverFactory('gurobi')#, solver_io='nl')
        opt_solver.options['threads'] = 4

        for k in range(scenarios):

            InflowsHydro = []
            InflowsHydro += [dict_format['inflow_hydro'][n][1][i-1][k] for n in range(len(hydroPlants))]

            for z in range(len(hydroPlants)):
                model.inflows[hydroPlants[z]] = InflowsHydro[z]
            for z in range(numAreas):
                for y in range(numBlocks):
                    model.meanWind[z+1,y+1] = dict_windenergy['windenergy_area'][z][i-1][k][y]

            # Reconstruct the instance and solve
            #instance.ctVol.reconstruct(); instance.ctGenW.reconstruct()
            #instance.dual = Suffix(direction=Suffix.IMPORT)

            action_map[manager.queue(model, opt=opt_solver, load_solutions=False)] = model
            #load_solutions=False

    for k in range(scenarios):

        a_m = manager.wait_any()
        instance_local=action_map[a_m]
        results = manager.get_results(a_m)
        #browse(results)

        # objective function value
        instance_local.solutions.load_from(results)
        sol_objective = instance_local.OBJ()
        #browse(instance_local)
        objective_list.append([sol_objective,k])
        total_obj += sol_objective

        # duals solution related to volume and storage-batteries conservation constraint
        d_object = getattr(model, 'ctVol')
        for plant in range(len(hydroPlants)):
            duals[plant].append(instance_local.dual[d_object[hydroPlants[plant]]])
            #print(d_object[hydroPlants[plant]])
        #print(duals)
        d_object = getattr(model, 'ctLvl')
        for plant in range(len(batteries)):
            duals_batt[plant].append(instance_local.dual[d_object[batteries[plant]]])

#        for constr in range(len(hydroPlants)):
#            obj_dual = results.solution.constraint['c'+str(constr)]
#            index_p = hydroPlants.index(sorted(hydroPlants)[constr])
#            duals[index_p].append(obj_dual.get('Dual'))
#
#        for constr in range(len(batteries)):
#            obj_dual = results.solution.constraint['c'+str(constr+len(hydroPlants))]
#            index_p = batteries.index(sorted(batteries)[constr])
#            duals_batt[constr].append(obj_dual.get('Dual'))

    return objective_list,duals_batt,duals,total_obj