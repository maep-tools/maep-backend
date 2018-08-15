#def aggregated():
    
stages = 60
scenarios = 10

import os
import csv, pickle
import openpyxl
from utils.readxlxs import xlxstocsvres

# Historical data wind
alldata = []; alldata2 = []
dict_fig ={}

path= 'resultsgeneral/'
for root,dirs,files in os.walk(path):
    xlsfiles=[ _ for _ in files if _.endswith('.xlsx') ]
    for xlsfile in xlsfiles:
        
        # import file
        importedfile = openpyxl.load_workbook(filename = os.path.join(root,xlsfile), read_only = True, keep_vba = False)
        
        tabnames = importedfile.get_sheet_names()
        xlxstocsvres(tabnames,'WindGen',importedfile)
        
        with open('resultsgeneral/'+'WindGen'+'.csv') as csvfile:
            readCSV = csv.reader(csvfile, delimiter=',')
            singleData = [[] for x in range(10)]  
            for row in readCSV:
                for col in range(10):
                    val = row[col+2]
                    try: 
                        val = float(val)
                    except ValueError:
                        pass
                    singleData[col].append(val)
        
        alldata.append(singleData)

vecdata = []
for i in range(len(alldata)):
    vec = []
    for z in range(60):
        y = 0
        for j in range(scenarios):
            y += sum(alldata[i][j][3+(z+1)*24-24:2+(z+1)*24])
        val = y/scenarios
        
        vec.append(val)
    vecdata.append(vec)
        
        
        
dict_charts = pickle.load( open( "savedata/html_save.p", "rb" ) )
dict_data = pickle.load( open( "savedata/data_save.p", "rb" ) )

#import plotly
#import plotly.graph_objs as go

#    genHFinal = dict_charts['genHFinal']
#    genTFinal = dict_charts['genTFinal']
#    genWFinal = dict_charts['genWFinal']
#    genDFinal = dict_charts['genDFinal']
#    genBFinal = dict_charts['genBFinal']
horizon = dict_data["horizon"]
#    numAreas = dict_data["numAreas"]
#    volData = dict_data["volData"]
#    thermalData = dict_data["thermalData"]
#    battData = dict_data["battData"]

import datetime
axisfixlow = horizon[0] + datetime.timedelta(hours = -360)
axisfixhig = horizon[stages-1] + datetime.timedelta(hours = 360)
x=horizon#list(range(1,stages+1))

###########################################################################

import plotly #.plotly as py
import plotly.graph_objs as go


# Create traces
trace0 = go.Scatter(
    x = x,
    y = vecdata[0],
    mode = 'lines',
    name = 'Mode 1'
)
trace1 = go.Scatter(
    x = x,
    y = vecdata[1],
    #mode = 'lines+markers',
    mode = 'lines',
    name = 'Mode 2'
)
trace2 = go.Scatter(
    x = x,
    y = vecdata[2],
    mode = 'lines',
    name = 'Mode 3',
    line =dict(
        color = ('rgb(0,100,80)')),
)
data = [trace0, trace1, trace2]

layout = go.Layout(
autosize=False,
width=800,
height=500,
#title='Double Y Axis Example',
yaxis=dict(title=' wind generation [MWh]',
           titlefont=dict(
                   family='Arial, sans-serif',
                   size=18,
                   color='darkgrey'),
           #tickformat = ".0f"
           exponentformat = "e",
           #showexponent = "none",
           ticks = "inside"
           ),
xaxis=dict(range=[axisfixlow,axisfixhig])
)
           
fig = go.Figure(data=data, layout=layout)
dict_fig["aggr"] = plotly.offline.plot(fig, output_type = 'div')

#        Wind = go.Scatter(
#            x=x,
#            y=y0_stck,
#            text=y0_txt,
#            hoverinfo='x+text',
#            mode='lines',
#            line=dict(width=0.5,
#                      color='rgb(224,243,248)'),
#            fill='tonexty',
#            name='Wind'
#        )
#        Hydro = go.Scatter(
#            x=x,
#            y=y1_stck,
#            text=y1_txt,
#            hoverinfo='x+text',
#            mode='lines',
#            line=dict(width=0.5,
#                      color='rgb(69,117,180)'),
#            fill='tonexty',
#            name='Hydro'
#        )
#        Thermal = go.Scatter(
#            x=x,
#            y=y3_stck,
#            text=y3_txt,
#            hoverinfo='x+text',
#            mode='lines',
#            line=dict(width=0.5,
#                      color='rgb(215,48,39)'),
#            fill='tonexty',
#            name='Thermal'
#        )
#        Batteries = go.Scatter(
#            x=x,
#            y=y2_stck,
#            text=y2_txt,
#            hoverinfo='x+text',
#            mode='lines',
#            line=dict(width=0.5,
#                      color='rgb(111, 231, 219)'),
#            fill='tonexty',
#            name='Batteries'
#        )
#        Deficit = go.Scatter(
#            x=x,
#            y=y4_stck,
#            text=y4_txt,
#            hoverinfo='x+text',
#            mode='lines',
#            line=dict(width=0.5,),
#                      #color='rgb(131, 90, 241)'),
#            fill='tonexty',
#            name='Deficit'
#        )
#        data = [Wind, Hydro, Thermal, Batteries, Deficit]
#        layout = go.Layout(
#        autosize=False,
#        width=800,
#        height=500,
#        #title='Double Y Axis Example',
#        yaxis=dict(title='Energy [MWh]',
#                   titlefont=dict(
#                           family='Arial, sans-serif',
#                           size=18,
#                           color='darkgrey'),
#                   #tickformat = ".0f"
#                   exponentformat = "e",
#                   #showexponent = "none",
#                   ticks = "inside"
#                   ),
#        xaxis=dict(range=[axisfixlow,axisfixhig])
#        )
#        #layout = go.Layout(showlegend=False)
#        fig = go.Figure(data=data, layout=layout)
#        # plotly.offline.plot(fig, filename='stacked-area-plot-hover', output_type = 'div')
#        dict_fig["string{0}".format(z+1)] = plotly.offline.plot(fig, output_type = 'div')
        
    ###########################################################################

    ###########################################################################
    
from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader('.'))
template = env.get_template("template_report5.html")

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

Html_file= open("results/results_report5.html","w")
Html_file.write(html_out)
Html_file.close()