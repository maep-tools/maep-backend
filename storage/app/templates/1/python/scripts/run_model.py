def execution(read_data, file,stages, seriesBack, seriesForw, param_calculation, sensDem,eps_area, eps_all,
              param_opf, wind_model2, eps_risk, commit, parallel, max_iter,results, policy, simulation,
              bnd_stages, curves, flow_gates, params):

    from scripts import main_model
    import sys

    errorFile = open("results/error.txt","w")
    errorFile.truncate()

    # read data
    try:
        main_model.data(read_data, file)
    except IOError as e:
        errorFile.write("READING DATA ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror))
        errorFile.close()        
        sys.exit()
    except ValueError:
        errorFile.write("Could not convert data 1")  
        errorFile.close()      
        sys.exit()
    except:
        errorFile.write("Unexpected error 1:"+ str(sys.exc_info()[0]))
        errorFile.close()   
        sys.exit()
    
    # data consistency
    try:    
        main_model.data_consistency(stages, seriesBack, seriesForw)
    except IOError as e:
        errorFile.write("CONSISTENCY DATA ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror)) 
        errorFile.close()       
        sys.exit()
    except ValueError:
        errorFile.write("Could not convert data 2")
        errorFile.close()
        sys.exit()
    except:
        errorFile.write("Unexpected error 2:" + str(sys.exc_info()[0]))    
        errorFile.close()   
        sys.exit()
    
    # parameters calculation
    try:    
        main_model.parameters(param_calculation, sensDem, stages, eps_area, eps_all)
    except IOError as e:
        errorFile.write("PARAMETERS CALCULATION ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror))
        errorFile.close()                                
        sys.exit()
    except ValueError:
        errorFile.write("Could not convert data")
        errorFile.close()                
        sys.exit()
    except:
        errorFile.write("Unexpected error 3:" + str(sys.exc_info()[0]))
        errorFile.close()                
        sys.exit()
    
    # opf pawer flow
    try:
        main_model.grid(param_opf, stages)
    except IOError as e:
        errorFile.write("POWER FLOW ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror))
        errorFile.close()        
        sys.exit()
    except ValueError:
         errorFile.write("Could not convert data") 
         errorFile.close()              
         sys.exit()
    except:
        errorFile.write("Unexpected error 4:" + str(sys.exc_info()[0]))
        errorFile.close()             
        sys.exit()
    
    # wind model 2 execution
    try:    
        main_model.wmodel2(wind_model2, stages, seriesBack)
    except IOError as e:
        errorFile.write("WIND MODEL 2 ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror))
        errorFile.close()              
        sys.exit()
    except ValueError:
         errorFile.write("Could not convert data") 
         errorFile.close()              
         sys.exit()
    except:
        errorFile.write("Unexpected error 5:" + str(sys.exc_info()[0])) 
        errorFile.close()              
        sys.exit()
    
    # optimization module
    try:
        main_model.optimization(stages, seriesBack, eps_risk, commit, parallel, param_opf, max_iter,
                            results, policy, simulation, seriesForw, bnd_stages, curves, flow_gates, params)
    except IOError as e:
        print("OPTIMIZATION ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror))        
        sys.exit("OPTIMIZATION ERROR _ I/O error({0}): {1}".format(e.errno, e.strerror))
    except ValueError:
        errorFile.write("Could not convert data")        
        errorFile.close()
        sys.exit()

    except:
        errorFile.write("Unexpected error 6:" + str(sys.exc_info()[0]))
        errorFile.close()
        sys.exit()