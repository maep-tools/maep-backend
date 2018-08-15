import plotly
import plotly.graph_objs as go

dict_fig ={}

# Add data
#x = ['hora1', 'hora2', 'hora3', 'hora4', 'hora5', 'hora6', 'hora7', 'hora8', 'hora9',
#     'hora10', 'hora11', 'hora12', 'hora13', 'hora14', 'hora15', 'hora16', 'hora17', 'hora18',
#     'hora19', 'hora20', 'hora21', 'hora22', 'hora23', 'hora24']

x = ['hour1', 'hour2', 'hour3', 'hour4', 'hour5', 'hour6', 'hour7', 'hour8', 'hour9',
     'hour10', 'hour11', 'hour12', 'hour13', 'hour14', 'hour15', 'hour16', 'hour17', 'hour18',
     'hour19', 'hour20', 'hour21', 'hour22', 'hour23', 'hour24']

x_rev = x[::-1]

# Line 1
y1 = [-1069.697541
,-1262.487273
,-1397.76
,-1397.76
,-1222.023186
,-1257.984
,-975.827757
,-313.9561776
,-419.328
,-114.0251945
,0
,0
,-49.15542497
,0
,0
,0
,0
,0
,0
,0
,0
,0
,-978.432
,-902.8974545
]

y2 = [0
,0
,0
,0
,0
,0
,0
,0
,0
,0
,360.4119878
,91.01507837
,0
,372.3823511
,589.3929873
,284.0901818
,259.3886723
,614.2928834
,707.0487273
,987.5083636
,239.9060751
,2.722909091
,0
,0
]

trace1 = go.Bar(
    x=x,
    y=y1,
    name='Load',
    marker=dict(
        color='rgb(55, 83, 109)'
    )
)
trace2 = go.Bar(
    x=x,
    y=y2,
    name='Discharge',
    marker=dict(
        color='rgb(26, 118, 255)'
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
        title = 'Variabilidad de la velocidad del viento',
        xaxis = dict(title = 'Month'),
        yaxis = dict(title = 'Energy [MWh]'),
              )

#fig = dict(data=data, layout=layout)
#py.iplot(fig, filename='styled-line')
fig = go.Figure(data=data, layout=layout)
dict_fig["aggr"] = plotly.offline.plot(fig, output_type = 'div')


from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader('.'))
template = env.get_template("template_report7.html")

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

Html_file= open("results/results_report7.html","w")
Html_file.write(html_out)
Html_file.close()