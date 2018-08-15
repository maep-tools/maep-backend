#' @Data_import_1, result_list, List resultado de la importacion utilizando data_load, contiene matriz de datos, matriz de fechas, informacion series
#' 
Data_import_1 <- function(datafolder,info_series,ffolder) {

# 1. Data load

# Import required functions

source(paste(ffolder,'data_load.R',sep=''))
source(paste(ffolder,'series_validation.R',sep=''))

# Funcion para extaer datos, entrega un list con 3 posiciones, 1. Matriz de informacion en formato "double", 
# 2. vector de fechas de todos las series utilizadas y 3. matriz de nombres, codigos, obtenida de 'info_hidro_var.csv'
Info_series_list<- data_load(datafolder,info_series) 
seleccion_list = series_validation(Info_series_list)

# Extraer series
m_series <- seleccion_list[[1]] # 

dates <- seleccion_list[[7]][[1]] #Extrae la informacion de fechas
monts_data <-seleccion_list[[7]][[2]] # Cantidad de meses en el horizonte temporal historico
m_dates <- seleccion_list[[7]][[3]] # crea una matriz de fechas para cada serie 

# 3. Extrate informaci?n de las series
dataN <- Info_series_list[[3]]
info_name <- seleccion_list[[2]]
namesS <- seleccion_list[[3]]
FP <- seleccion_list[[4]]#Factor de produccion
resource_type <- seleccion_list[[5]]
inst_cap = seleccion_list[[6]]

return(list(m_series,dates,monts_data,m_dates,dataN,info_name,namesS,FP,resource_type,inst_cap))

}