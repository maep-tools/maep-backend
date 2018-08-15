energy_y_H <- function(tipo_recurso, ts_series_i, FP) {
  
  indx <- which(tipo_recurso == 1)
  
  energy_y_hidro <- list()
  sum_energy = array(dim=c(1,length(indx)))
  sum_energy_hidro <- list()
  
  contador = 1
  for (i in indx){
    energy_y_hidro[[contador]]<-(ts_series_i[[i]]*FP[i]*720)/1000
    sum_energy <-colSums(energy_y_hidro[[contador]])
    sum_energy_hidro[[contador]]<-sum_energy
    contador = contador +1
  }
  
  energia <-list()
  energia[[1]] = energy_y_hidro #matriz de generación por VAR
  energia[[2]] = sum_energy_hidro #suma de generacion anual VAR
  
  return(energia)
}

