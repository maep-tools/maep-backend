#' @result_list, result_list, List resultado de la importacion utilizando data_load, contiene matriz de datos, matriz de fechas, informacion series

series_validation <- function(result_list) {
  # 1. Seleccionar información y definir si hay valores erroneos (Para este caso -99.), marcarlos como NaN
  
  for (i in 1:nrow(result_list[[1]])) {
    for (j in 1:ncol(result_list[[1]])) {
      if (result_list[[1]][i,j]<0) {
        result_list[[1]][i,j] = NaN
      }
    }
  }
  
  # 2. Buscar series con errores y excluirlas 
  avg_nan = colMeans(result_list[[1]])
  indice_Nonan = which(is.nan(avg_nan)==FALSE)    #Series sin errores
  indice_nan = which(is.nan(avg_nan))             #Series marcadas con errores
  
  a=0
  m_series = array(dim=c(nrow(result_list[[1]]),length(indice_Nonan)))
  for (i in indice_Nonan) {
    a=a+1
    m_series[,a] <- as.vector(result_list[[1]][,i])
  }
  
  # 3. Extraer fechas
  fechas <- result_list[[2]]                   #Extrae la informacion de fechas
  monts_data <-nrow(result_list[[1]])          # Cantidad de meses en el horizonte temporal historico
  m_dates <- matrix(as.list(fechas), monts_data)  # crea una matriz de fechas para cada serie
  
  fechas_list = list()
  fechas_list[[1]] = fechas
  fechas_list[[2]] = monts_data
  fechas_list[[3]] = m_dates
  
  # 4. Extrate informacion de las series seleccionadas como NoN
  dataN <- result_list[[3]]
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
  
  adjusted_series = list()
  adjusted_series[[1]]= m_series
  adjusted_series[[2]]= info_name #codes
  adjusted_series[[3]]= namesS #names
  adjusted_series[[4]]= FP
  adjusted_series[[5]]= tipo_recurso
  adjusted_series[[6]]= cap_inst
  adjusted_series[[7]]= fechas_list
  
  
  return(adjusted_series)
}





