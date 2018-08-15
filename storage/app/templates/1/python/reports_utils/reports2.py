#def aggregated():
    
stages = 60
scenarios = 10

import os
import csv, pickle
import openpyxl
from utils.readxlxs import xlxstocsvres
from statistics import mean

# Historical data wind
alldata = []
dict_fig ={}

path= 'resultsgeneral/'
for root,dirs,files in os.walk(path):
    xlsfiles=[ _ for _ in files if _.endswith('.xlsx') ]
    for xlsfile in xlsfiles:
        
        # import file
        importedfile = openpyxl.load_workbook(filename = os.path.join(root,xlsfile), read_only = True, keep_vba = False)
        
        tabnames = importedfile.get_sheet_names()
        xlxstocsvres(tabnames,'AggLevelHydro',importedfile)
        
        with open('resultsgeneral/'+'AggLevelHydro'+'.csv') as csvfile:
            readCSV = csv.reader(csvfile, delimiter=',')
            singleData = [[] for x in range(1)]  
            for row in readCSV:
                for col in range(1):
                    val = row[col+2]
                    try: 
                        val = float(val)
                    except ValueError:
                        pass
                    singleData[col].append(val)
        
        alldata.append(singleData)

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
volData = dict_data["volData"]
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
    y = alldata[0][0][2:],
    mode = 'lines',
    name = 'Modo 1',
    line = dict(
        color = 'rgb(70,130,180)',
        width = 1.5) # dash options include 'dash', 'dot', and 'dashdot'
)
trace01 = go.Scatter(
    x = x,
    y = [mean(alldata[0][0][2:])]*60,
    mode = 'lines',
    #name = 'Modo promedio - promedio',
    showlegend = False,
    line = dict(
        color = 'rgb(70,130,180)',
        width = 0.7,
        dash = 'dash') # dash options include 'dash', 'dot', and 'dashdot'
)
trace2 = go.Scatter(
    x = x,
    y = alldata[1][0][2:],
    mode = 'lines',
    name = 'Mode 2',
    line = dict(
        color = 'rgb(255,140,0)',
        width = 1.5) # dash options include 'dash', 'dot', and 'dashdot'
)
trace02 = go.Scatter(
    x = x,
    y = [mean(alldata[1][0][2:])]*60,
    mode = 'lines',
    #name = 'Modo horario - promedio',
    showlegend = False,
    line = dict(
        color = 'rgb(255,140,0)',
        width = 0.7,
        dash = 'dash') # dash options include 'dash', 'dot', and 'dashdot'
)
trace3 = go.Scatter(
    x = x,
    y = alldata[2][0][2:],
    mode = 'lines',
    name = 'Mode 3',
    line = dict(
        color = 'rgb(46,139,87)',
        width = 1.5) # dash options include 'dash', 'dot', and 'dashdot'
)
trace03 = go.Scatter(
    x = x,
    y = [mean(alldata[2][0][2:])]*60,
    mode = 'lines',
    #name = 'Modo variable - promedio',
    #visible = 'legendonly',
    showlegend = False,
    line = dict(
        color = 'rgb(46,139,87)',
        width = 0.7,
        dash = 'dash') # dash options include 'dash', 'dot', and 'dashdot'
)
data = [trace0, trace01, trace2, trace02, trace3, trace03]

layout = go.Layout(
autosize=False,
width=900,
height=500,
#title='Double Y Axis Example',
yaxis=dict(title='Level of aggregate reservoir[Hm3]',
           titlefont=dict(
                   family='Arial, sans-serif',
                   size=18,
                   color='darkgrey'),
           #tickformat = ".0f"
           exponentformat = "e",
           #showexponent = "none",
           ticks = "inside"
           ),
#annotations=[
#        dict(
#            x=horizon[61],
#            y=mean(alldata[0][0][2:])*1.002,
#            xref='x',
#            yref='y',
#            text='67.5%',
#            showarrow=False,
#            arrowhead=1,
#            ax=+50,
#            ay=0
#        ),
#        dict(
#            x=horizon[61],
#            y=mean(alldata[1][0][2:])*1.0151,
#            xref='x',
#            yref='y',
#            text='68.8%',
#            showarrow=False,
#            arrowhead=1,
#            ax=+50,
#            ay=0
#        ),
#        dict(
#            x=horizon[61],
#            y=mean(alldata[2][0][2:])*0.985,
#            xref='x',
#            yref='y',
#            text='66.7%',
#            showarrow=False,
#            arrowhead=1,
#            ax=+50,
#            ay=0
#        )],
xaxis=dict(range=[axisfixlow,axisfixhig])
)
           
fig = go.Figure(data=data, layout=layout)
    # plotly.offline.plot(fig, filename='line-mode', output_type = 'div')
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
template = env.get_template("template_report2.html")

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

Html_file= open("results/results_report2.html","w")
Html_file.write(html_out)
Html_file.close()