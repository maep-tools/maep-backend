#setwd('C:/Users/ab.pedraza1391/SharePoint/AP_documentos - Documentos/Compilacion VAR/Bitbuket_carpeta_compartida_JFP/seriescomplementariedad/test') # Install tools and libraries 
graphics.off()
library(fpp)
library(gdata)
require(tseries)
require(MTS)
require(graphics)
require(Metrics)
require(forecast)
require(corrplot)
library(xlsx)

# set wd
setwd(".") #first open VAR_main 
#setwd("../../test/")

args <- commandArgs(TRUE)
lag_max = strtoi(args[1])
testing_t = strtoi(args[2])
d_correl = strtoi(args[3])




ffolder = '../main/funciones/'
source(paste(ffolder,'Data_import_1.R',sep=''))
source(paste(ffolder,'model_setup.R',sep=''))
source(paste(ffolder,'synthetic_generation.R',sep=''))

######################## Stage 1. Data load ####################################

# define folder
datafolder = '../main/series_hidricas_sistema_completo'

# define file
#info_series ='info_var_25.csv' # 13 plantas - problema con p=12
#info_series ='info_var_base.csv' # 8 plantas - problema con p=12
#info_series ='info_var_100.csv' # 27 plantas - problema con p=12
#info_series ='info_var_auct.csv' # evaluación de subastas
info_series ='info_var_base.csv' # p=[3-5]

# dala load and assessmet
main_data_file <- Data_import_1(datafolder,info_series,ffolder)

# Output Data_import_1
# return(list(m_series,dates,monts_data,m_dates,dataN,info_name,namesS,FP,resource_type,inst_cap))

######################## Stage 2. Model setup #################################

m_series <- main_data_file[[1]]
m_dates <- main_data_file[[4]]
info_codes  <- main_data_file[[6]]
names <- main_data_file[[7]]

#if testing_t <- 1, it uses 80% of the provided period to train the model and the remaining 20% to test it
#lag_max =12
#testing_t <- 0
#d_correl <- 0
seasonality <- 12 #NULL

SETUP_model <- model_setup(m_series,m_dates,info_codes,testing_t,lag_max,seasonality,names,ffolder)

# Output model_setup
# return(list(best_p_acfres,best_p_pacfres, p_model,VARts_series[[p_model]]))

######################## Stage 3. forecast #################################

forecast_periods <- 3 #(test time, stablished by user 1 to 20 years)
scenarios <- 6

forecasted_data=synthetic_simulaton(m_series, names, SETUP_model[[4]],scenarios,forecast_periods,main_data_file[[5]][[2]])

# Output synthetic_simulaton
#return(list(Inflow_hydro, Inflow_wind,Inflow_matrix))

######################## Stage 4. save data #################################

Inflow = as.data.frame(forecasted_data[[1]])
InflowWind =as.data.frame(forecasted_data[[2]])

# Location and name of the file must be selected by the us[er 

write.csv(x = Inflow,file = "./Forecast_data/Inflow.csv")
write.csv(x = InflowWind,file = "./Forecast_data/InflowWind.csv")
