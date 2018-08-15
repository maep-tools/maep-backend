''' Version 7.8 Planning model'''

# packages
import timeit
from scripts import run_model

# Simulation time
start = timeit.default_timer()

import sys, json
import logging
logging.warning(sys.argv[0])
sys.stdout.flush()
params=json.loads(sys.argv[1])

# logging.warning('Ejecutando python en el modelo' + str(params['id']))
# logging.warning('procesando ' + params['name'])
# logging.warning('phase = ' + str(params['phase']))
# logging.warning('statusId = ' + str(params['statusId']))
# logging.warning('templateId = ' + str(params['templateId']))
# logging.warning('max_iter = ' + str(params['max_iter']))
# logging.warning('stag= = ' + str(params['stages']))
# logging.warning('seriesBack = ' + str(params['seriesBack']))
# logging.warning('seriesForw = ' + str(params['seriesForw']))
# logging.warning('variance = ' + str(params['variance']))
# logging.warning('sensDem = ' + str(params['sensDem']))
# logging.warning('speed_out = ' + str(params['speed_out']))
# logging.warning('speed_in = ' + str(params['speed_in']))
# logging.warning('eps_area = ' + str(params['eps_area']))
# logging.warning('eps_all = ' + str(params['eps_all']))
# logging.warning('eps_risk = ' + str(params['eps_risk']))
# logging.warning('commit = ' + str(params['commit']))
# logging.warning('lag_max = ' + str(params['lag_max']))
# logging.warning('testing_t = ' + str(params['testing_t']))
# logging.warning('d_correl = ' + str(params['d_correl']))
# logging.warning('seasonality = ' + str(params['seasonality']))

#==============================================================================

# Parameters simulation
#file = '2areasprueba'
file = str(params['id'])
#max_iter = 1 
max_iter = params['max_iter'] # Maximun number of iterations
#bnd_stages = 1          # Boundary stages
bnd_stages = params['bnd_stages']          # Boundary stages
#stages = 5 + bnd_stages # planning horizon: (months + bundary months)
stages = params['stages'] + bnd_stages # planning horizon: (months + bundary months)

#stages = 2 + bnd_stages # planning horizon: (months + bundary months)
#seriesBack = 1          # series used in the backward phase
seriesBack = params['seriesBack']          # series used in the backward phase
#seriesForw = 1          # series used in the forward phase
seriesForw = params['seriesForw']          # series used in the forward phase

# Parameters analysis
#sensDem = 1.0   # Demand factor
sensDem = params['sensDem']   

#eps_area = 0.5  # Short-term - significance level area
eps_area = params['eps_area']
#eps_all = 0.5   # Short-term - significance level for the whole system
eps_all = params['eps_all']  # Short-term - significance level for the whole system
#eps_risk = 0.1  # long-term risk
eps_risk = params['eps_risk']
#commit = 0.0    # risk-measure comminment
commit = params['commit']



# read data options
read_data = params['read_data']
param_calculation = params['param_calculation'] 
param_opf = params['param_opf']         # OPF model
wind_model2 = params['wind_model2']      # Second model of wind plants
flow_gates = params['flow_gates']        # Security constraints   

# operation model options
policy = params['policy']
simulation = params['simulation']
parallel = params['parallel'] 

# PRINT ORDER: 1. dispatch curves, 2. marginal cost
results = params['results']
curves = params['curves']

import redis

r = redis.StrictRedis(host='localhost', port=6379, db=0)
logging.warning('room/' + str(params['id']))

#== Formato del mensaje
data = {}
data['id'] =  str(params['id'])
data['state'] = 'Inicializado'
data['message'] = 'Los datos han sido cargados'
data['phase'] = 3
data['percent'] = 0
json_data = json.dumps(data)
r.publish('room/' +str(params['id']), json_data)
logging.warning('Información cargada')
#==============================================================================
#run model
run_model.execution(read_data, file,stages, seriesBack, seriesForw, param_calculation, 
                    sensDem,eps_area, eps_all,param_opf, wind_model2, eps_risk, commit, 
                    parallel, max_iter,results, policy, simulation, bnd_stages, curves,
                    flow_gates, params)
#==============================================================================

# print execution time
end = timeit.default_timer()
print(end - start)

# Enviamos el evento para indicar que ha sido procesado
data = {}
data['id'] =  str(params['id'])
data['state'] = 'Finalizado'
data['phase'] = 3
data['message'] = 'El modelo ha sido procesado'
data['percent'] = 100
json_data = json.dumps(data)
r.publish('room/' +str(params['id']), json_data)
logging.warning('Finalizado')

# HOMEWORK

# sedimentation curve
# unit commitment
# double circuits
# speed indices
# loses transmmision
# opf constraint
# distributed solar
# emissions

