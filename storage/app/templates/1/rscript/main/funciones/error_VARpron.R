
error_VARpron<- function(maxP,ts_series_i,ts_comp) {
  #Calculo error porcential periodo de comparación vs cada VAR(i)
  #Diferencia puntual para cada serie Vs su valor real 
  VARi_error_porcentual = list()
  for (i in 1:length(ts_series_i)) {
    ts_data_VARi = ts_series_i[[i]]
    diferencia = (ts_comp[,i]-ts_data_VARi)*100/ts_comp[,i]
    VARi_error_porcentual[[i]]=diferencia 
  }
  return(VARi_error_porcentual)
}
