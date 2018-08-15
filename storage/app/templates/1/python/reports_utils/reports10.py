import plotly
import plotly.graph_objs as go

dict_fig ={}

labels = ['Oxygen','Hydrogen','Carbon_Dioxide','Nitrogen']
values = [4500,2500,1053,500]

trace03 = go.Pie(
    labels = labels,
    values = values,
)

#layout = go.Layout(
#autosize=False,
#width=1000,
#height=500,
##title='Double Y Axis Example',
#yaxis=dict(title=' Costo marginal [$/MWh]',
#           titlefont=dict(
#                   family='Arial, sans-serif',
#                   size=18,
#                   color='darkgrey'),
#           #tickformat = ".0f"
#           exponentformat = "e",
#           #showexponent = "none",
#           ticks = "inside"
#           ),
#xaxis=dict(range=[axisfixlow,axisfixhig])
#)

#fig = dict(data=data, layout=layout)
#py.iplot(fig, filename='styled-line')
#fig = go.Pie(data=data)#, layout=layout)
dict_fig["aggr"] = plotly.offline.plot(trace03, output_type = 'div')


from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader('.'))
template = env.get_template("template_report10.html")

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

Html_file= open("results/results_report10.html","w")
Html_file.write(html_out)
Html_file.close()