import plotly
import plotly.graph_objs as go

dict_fig ={}

# Add data
x = ['hour1', ' ', '  ', '   ', '    ', 'hour6', '     ', '      ', '       ',
     '        ', '         ', 'hour12', '          ', '           ', '            ', '             ', '              ', 'hour18',
     '               ', '                ', '                 ', '                  ', '                   ', 'hour24']

x_rev = x[::-1]

# Line 1
y1 = [8.755612572,13.16228352
,9.313662604
,6.176395125
,6.924951892
,10.89159718
,11.37652341
,13.62347659
,12.81141758
,15.50994227
,7.716484926
,6.581141758
,10.52726106
,13.12379731
,10.5086594
,12.27132777
,13.46632457
,11.69980757
,12.93136626
,7.317511225
,6.875561257
,6.350224503
,6.234765876
,5.849903784
]
y1_upper = [10.0251764
,13.51766517
,10.27296985
,7.028737652
,7.285049391
,12.66692752
,12.22976267
,15.99396151
,13.73383964
,18.03806286
,8.171757537
,7.410365619
,10.8430789
,15.39421424
,11.570034
,14.63969403
,14.57056318
,11.94550353
,12.98309173
,8.06389737
,7.16433483
,7.016998076
,6.633790892
,6.727389352]

y1_lower = [7.135824246
,11.02999359
,9.173957665
,5.18199551
,5.754635022
,9.791545863
,10.02271713
,12.09764721
,11.12031046
,12.65611289
,6.713341886
,6.265246953
,10.49567928
,11.15522771
,9.447284798
,11.91545927
,11.28477999
,9.441744708
,10.40974984
,6.197932008
,6.579912123
,5.162732521
,5.692341244
,5.79725465]

y1_lower = y1_lower[::-1]

## Line 2
y2 = [10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10,
      10, 10, 10, 10]
#y2_upper = [10.0251764
#,12.51766517
#,10.27296985
#,10.028737652
#,10.285049391
#,11.66692752
#,12.22976267
#,11.99396151
#,11.73383964
#,12.03806286
#,10.171757537
#,10.410365619
#,10.8430789
#,11.39421424
#,11.570034
#,10.63969403
#,11.57056318
#,10.94550353
#,11.98309173
#,10.06389737
#,10.16433483
#,10.016998076
#,10.633790892
#,10.727389352]
#
#y1_lower = [7.135824246
#,11.02999359
#,9.173957665
#,5.18199551
#,5.754635022
#,9.791545863
#,10.02271713
#,12.09764721
#,11.12031046
#,12.65611289
#,6.713341886
#,6.265246953
#,10.49567928
#,11.15522771
#,9.447284798
#,11.91545927
#,11.28477999
#,9.441744708
#,10.40974984
#,6.197932008
#,6.579912123
#,5.162732521
#,5.692341244
#,5.79725465]
#
## Line 3
y3 = [8.755612572,12.16228352
,9.313662604
,6.176395125
,6.924951892
,10.89159718
,11.37652341
,13.62347659
,12.81141758
,15.50994227
,7.716484926
,7.081141758
,10.52726106
,13.12379731
,10.5086594
,12.27132777
,13.46632457
,11.69980757
,11.23136626
,7.317511225
,6.875561257
,6.350224503
,6.234765876
,5.849903784
]
#y3_upper = [11, 9, 7, 5, 3, 1, 3, 5, 3, 1]
#y3_lower = [9, 7, 5, 3, 1, -.5, 1, 3, 1, -1]
#y3_lower = y3_lower[::-1]

trace1 = go.Scatter(
    x=x+x_rev,
    y=y1_upper+y1_lower,
    fill='tozerox',
    fillcolor='rgba(0,100,80,0.3)',
    line=dict(
        color = ('transparent')),
    showlegend=False,
    name='Fair',
)
#trace2 = go.Scatter(
#    x=x+x_rev,
#    y=y2_upper+y2_lower,
#    fill='tozerox',
#    fillcolor='rgba(0,176,246,0.2)',
#    #line=Line(color='transparent'),
#    name='Premium',
#    showlegend=False,
#)
#trace3 = go.Scatter(
#    x=x+x_rev,
#    y=y3_upper+y3_lower,
#    fill='tozerox',
#    fillcolor='rgba(231,107,243,0.2)',
#    #line=Line(color='transparent'),
#    showlegend=False,
#    name='Fair',
#)
trace4 = go.Scatter(
    x=x,
    y=y1,
    #line=Line(color='rgb(0,100,80)'),
    mode='lines',
    name='Fair',
)
trace5 = go.Scatter(
    x=x,
    y=y2,
    #line=Line(color='rgb(0,176,246)'),
    mode='lines',
    name='Average',
)
trace6 = go.Scatter(
    x=x,
    y=y3,
    #line=Line(color='rgb(231,107,243)'),
    mode='markers',
    name='Hourly',
    marker = dict(size = 10,
                       color = 'rgba(255, 182, 193, .9)',
                       line = dict(color = 'rgba(152, 0, 0, .8)',
                                   width = 2))
)

data = [trace1, trace5, trace6]

# Edit the layout
layout = dict(
        autosize=False,
        width=1100,
        height=500,
        legend=dict(
                font=dict(
                        #family='sans-serif',
                        size=17,
                        color='black'
                        )),
        xaxis = dict(title = 'Single period',
                     titlefont=dict(
                             #family='Arial, sans-serif',
                             size=19,
                             color='black'),
                     tickfont=dict(
                    #family='Old Standard TT, serif',
                    size=17,
                    color='black'
                        ),
                     #color='black',
                     tickangle=0),
        yaxis = dict(title = 'Speed (m/s)',
                     tickfont=dict(
                    #family='Old Standard TT, serif',
                    size=17,
                    color='black'
                        ),
                     titlefont=dict(
                             #family='Arial, sans-serif',
                             size=19,
                             color='black'),
                    )
                )

#fig = dict(data=data, layout=layout)
#py.iplot(fig, filename='styled-line')
fig = go.Figure(data=data, layout=layout)
dict_fig["aggr"] = plotly.offline.plot(fig, output_type = 'div', show_link=False)


from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader('.'))
template = env.get_template("template_report6.html")

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

Html_file= open("results/results_report6.html","w")
Html_file.write(html_out)
Html_file.close()