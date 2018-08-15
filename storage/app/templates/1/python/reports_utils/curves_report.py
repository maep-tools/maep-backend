def marginalcost(scenarios,stages):
    
    import pickle
    dict_data = pickle.load( open( "savedata/data_save.p", "rb" ) )
    
    import csv
    #import pickle
    import openpyxl
    from utils.readxlxs import xlxstocsvres
    
    horizon = dict_data["horizon"]
    numAreas = dict_data["numAreas"]
    
    # Historical data wind
    dict_fig ={}
    
    importedfile = openpyxl.load_workbook('results/Results.xlsx', read_only = True, keep_vba = False)
            
    tabnames = importedfile.get_sheet_names()
    xlxstocsvres(tabnames,'MarginalArea',importedfile)
            
    with open('results/csv_variables/'+'MarginalArea'+'.csv') as csvfile:
        readCSV = csv.reader(csvfile, delimiter=',')
        singleData = [[] for x in range(numAreas)]  
        for row in readCSV:
            for col in range(numAreas):
                val = row[col+1]
                try: 
                    val = float(val)
                except ValueError:
                    pass
                singleData[col].append(val)
    
    import datetime
    axisfixlow = horizon[0] + datetime.timedelta(hours = -360)
    axisfixhig = horizon[stages-1] + datetime.timedelta(hours = 360)
    x=horizon#list(range(1,stages+1))
    # x_rev = x[::-1]
    
    tabnames = importedfile.get_sheet_names()
    xlxstocsvres(tabnames,'MarginalCost',importedfile)
    
    with open('results/csv_variables/'+'MarginalCost'+'.csv') as csvfile:
        readCSV = csv.reader(csvfile, delimiter=',')
        maxData = [[] for x in range(numAreas)] 
        minData = [[] for x in range(numAreas)] 
        Data = [[] for x in range(numAreas)]
        
        for row in readCSV:
            for col in range(numAreas):
                auxval = []
                for k in range(scenarios):
                    val = row[(k+1)+(col+1)*scenarios-scenarios]
                    try: 
                        val = float(val)
                    except ValueError:
                        pass
                    auxval.append(val)
                Data[col].append(auxval)
                
        for col in range(numAreas):
            for stage in range(stages):
                maxData[col].append(max(Data[col][stage+3])) 
                minData[col].append(min(Data[col][stage+3]))
        
    ############################################################################
    
    import plotly 
    import plotly.graph_objs as go
    
    # Create traces
    trace0 = go.Scatter(
        x = x,
        y = singleData[3][2:],
        mode = 'lines',
        name = singleData[3][1]
    )
    trace1a = go.Scatter(
        x=x,
        y=minData[5],
        fill=None,
        fillcolor='rgba(0,100,80,0.3)',
        line=dict(
            color = ('transparent')),
        showlegend=False,
        name='Fair',
    )
    trace1b = go.Scatter(
        x=x,
        y=maxData[5],
        fill='tonexty',
        fillcolor='rgba(0,100,80,0.3)',
        line=dict(
            color = ('transparent')),
        showlegend=False,
        name='Fair',
    )
    trace5 = go.Scatter(
        x = x,
        y = singleData[5][2:],
        mode = 'lines',
        name = singleData[5][1]
    )
    trace6 = go.Scatter(
        x = x,
        y = singleData[14][2:],
        mode = 'lines',
        name = singleData[14][1]
    )
    data = [trace0, trace1a, trace1b, trace5, trace6]
    
    layout = go.Layout(
    autosize=False,
    width=1000,
    height=500,
    #title='Double Y Axis Example',
    yaxis=dict(title=' Marginal cost [$/MWh]',
               titlefont=dict(
                       family='Arial, sans-serif',
                       size=18,
                       color='darkgrey'),
               #tickformat = ".0f"
               exponentformat = "e",
               #showexponent = "none",
               ticks = "inside",
               range=[20,100]
               ),
    xaxis=dict(range=[axisfixlow,axisfixhig])
    )
               
    fig = go.Figure(data=data, layout=layout)
    dict_fig["aggr"] = plotly.offline.plot(fig, output_type = 'div')
    
    ###########################################################################
        
    from jinja2 import Environment, FileSystemLoader
    
    env = Environment(loader=FileSystemLoader('.'))
    template = env.get_template("templates/marginalcost_report.html")
    
    template_vars = {"title" : "Report",
                     "data1": "Each area dispatch",
                     "div_placeholder1A": dict_fig["aggr"]
                     #"div_placeholder1B": dict_fig["string2"],
                     #"div_placeholder1C": dict_fig["string3"],
                     #"div_placeholder1D": dict_fig["string4"],
                     #"div_placeholder1E": dict_fig["string5"],
                     #"data2": "All areas",
                     #"div_placeholder2": graf3,
                     #"data3": ,
                     #"div_placeholder3": ,
                     #"data4": ,
                     #"div_placeholder4": 
                     }
    
    html_out = template.render(template_vars)
    
    Html_file= open("results/marginalcost_report.html","w")
    Html_file.write(html_out)
    Html_file.close()
    
    return minData