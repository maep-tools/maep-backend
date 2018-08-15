def paramlimits(stages,seriesBack,seriesForw,noStages):
    
    import sys
    
    if stages > noStages:
        sys.exit('Number of stages exceeds the maximum number allowed by the data of the system ('+str(noStages)+' stages)')

def areavalidation(area,substring):
    
    import sys
    
    sys.exit('Area or node "' +str(area) + '" does not exist or it is inactive - sheet '+substring)
