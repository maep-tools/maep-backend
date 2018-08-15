#' @resultado_list, resultado_list, List resultado de la importacion utilizando data_load, contiene matriz de datos, matriz de fechas, informacion series

correccion_series <- function(resultado_list) {
  # 1. Seleccionar información y definir si hay valores erroneos (Para este caso -99.), marcarlos como NaN
  # JUAN: realmente marca los valores negativos como NaN
  for (i in 1:nrow(resultado_list[[1]])) {
    for (j in 1:ncol(resultado_list[[1]])) {
      if (resultado_list[[1]][i,j]<0) {
        resultado_list[[1]][i,j] = NaN
      }
    }
  }
  
  # 2. Buscar series con errores y excluirlas 
  avg_nan = colMeans(resultado_list[[1]])
  indice_Nonan = which(is.nan(avg_nan)==FALSE) #Series sin errores
  indice_nan = which(is.nan(avg_nan)) #Series marcadas con errores
  
  a=0
  m_series = array(dim=c(nrow(resultado_list[[1]]),length(indice_Nonan)))
  for (i in indice_Nonan) {
    a=a+1
    m_series[,a] <- as.vector(resultado_list[[1]][,i])
  }
  # 2. Extraer fechas
  fechas <- resultado_list[[2]] #Extrae la informaci?n de fechas
  monts_data <-nrow(resultado_list[[1]]) # Cantidad de meses en el horizonte temporal historico
  m_dates <- matrix(as.list(fechas), monts_data) # crea una matriz de fechas para cada serie
  
  fechas_list = list()
  fechas_list[[1]] = fechas
  fechas_list[[2]] = monts_data
  fechas_list[[3]] = m_dates
  
  # 3. Extrate informacion de las series seleccionadas como NoN
  dataN <- resultado_list[[3]]
  info_name = array(dim=c(1,length(indice_Nonan)))
  namesS = array(dim=c(1,length(indice_Nonan)))
  FP = array(dim=c(1,length(indice_Nonan)))
  tipo_recurso = array(dim=c(1,length(indice_Nonan)))
  cap_inst = array(dim=c(1,length(indice_Nonan)))
  
  a=0
  for(i in indice_Nonan){
    a = a+1
    info_name[a] <- dataN[,1][i]
    namesS[a] <- paste0(dataN[,5][i])
    FP[a] <- dataN[,7][i]
    tipo_recurso[a] <- dataN[,2][i]
    cap_inst[a] = dataN[,6][i]
  }
  
  series_corregidas = list()
  series_corregidas[[1]]= m_series
  series_corregidas[[2]]= info_name
  series_corregidas[[3]]= namesS
  series_corregidas[[4]]= FP
  series_corregidas[[5]]= tipo_recurso
  series_corregidas[[6]]= cap_inst
  series_corregidas[[7]]= fechas_list
  
  
  return(series_corregidas)
}





