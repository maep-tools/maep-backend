
def gurobi_solver(SolverFactory):
    
    # Create a solver
    #opt = SolverFactory('gurobi')
    opt = SolverFactory('gurobi', solver_io='python')
    opt.options['Threads'] = 8
    opt.options['OutputFlag'] = 0
    opt.options['keepfiles'] = False

    return opt

def glpk_solver(SolverFactory):

    # Create a solver
    opt = SolverFactory('glpk')
    
    return opt

def cplex_solver(SolverFactory):

    # Create a solver
    #opt = SolverFactory('cplex',solver_io='python')
    opt = SolverFactory('cplex')
    #opt = SolverFactory('cplex',solver_io='nl')
    #opt = SolverFactory('cplexamp')
    
    return opt