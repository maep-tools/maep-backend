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
        xlxstocsvres(tabnames,'BatteriesGen',importedfile)
        
        with open('resultsgeneral/'+'BatteriesGen'+'.csv') as csvfile:
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
            y += sum(alldata[i][j][4+(z+1)*24-24:2+(z+1)*24])
        val = y/scenarios
        
        vec.append(val)
    vecdata.append(vec)
        
# dowload
for root,dirs,files in os.walk(path):
    xlsfiles=[ _ for _ in files if _.endswith('.xlsx') ]
    for xlsfile in xlsfiles:
        
        # import file
        importedfile = openpyxl.load_workbook(filename = os.path.join(root,xlsfile), read_only = True, keep_vba = False)
        
        tabnames = importedfile.get_sheet_names()
        xlxstocsvres(tabnames,'LoadBatt',importedfile)
        
        with open('resultsgeneral/'+'LoadBatt'+'.csv') as csvfile:
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
        
        alldata2.append(singleData)

vecdata2 = []
for i in range(len(alldata2)):
    vec = []
    for z in range(60):
        y = 0
        for j in range(scenarios):
            y += sum(alldata2[i][j][4+(z+1)*24-24:2+(z+1)*24])
        val = y/scenarios
        
        vec.append(val)
    vecdata2.append(vec)      
        

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


import plotly
import plotly.graph_objs as go

dict_fig ={}

# Add data
#x = ['hora1', 'hora2', 'hora3', 'hora4', 'hora5', 'hora6', 'hora7', 'hora8', 'hora9',
#     'hora10', 'hora11', 'hora12', 'hora13', 'hora14', 'hora15', 'hora16', 'hora17', 'hora18',
#     'hora19', 'hora20', 'hora21', 'hora22', 'hora23', 'hora24']


trace1 = go.Bar(
    x=x,
    y=vecdata[0],
    name='Modo promedio',
    marker=dict(
        color='rgb(8,48,107)'
    )
)
trace2 = go.Bar(
    x=x,
    y=vecdata[2],
    name='Modo variable',
    marker=dict(
        color='rgb(158,202,225)'
    )
)

data = [trace1, trace2]
layout = go.Layout(
    title='US Export of Plastic Scrap',
    xaxis=dict(
        tickfont=dict(
            size=14,
            color='rgb(107, 107, 107)'
        )
    ),
    yaxis=dict(
        title='USD (millions)',
        titlefont=dict(
            size=16,
            color='rgb(107, 107, 107)'
        ),
        tickfont=dict(
            size=14,
            color='rgb(107, 107, 107)'
        )
    ),
    legend=dict(
        x=0,
        y=1.0,
        bgcolor='rgba(255, 255, 255, 0)',
        bordercolor='rgba(255, 255, 255, 0)'
    ),
    barmode='group',
    bargap=0.15,
    bargroupgap=0.1
)

# Edit the layout
layout = dict(
        autosize=False,
        width=1100,
        height=500,
        #title = 'Variabilidad de la velocidad del viento',
        #xaxis = dict(title = 'Month'),
        yaxis = dict(title = 'Energía [MWh]'),
              )

#fig = dict(data=data, layout=layout)
#py.iplot(fig, filename='styled-line')
fig = go.Figure(data=data, layout=layout)
dict_fig["aggr"] = plotly.offline.plot(fig, output_type = 'div')


from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader('.'))
template = env.get_template("template_report8.html")

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

Html_file= open("results/results_report8.html","w")
Html_file.write(html_out)
Html_file.close()