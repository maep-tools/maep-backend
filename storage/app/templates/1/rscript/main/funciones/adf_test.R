adf_test <- function(info_name,ts_series) {
  
  ##############################################################################################################
  # PRUEBAS DE ESTACIONARIEDAD POR SERIE EN LA MATRIS TS_SERIES
  #-------------------------------------------------------------------------------------------------------------
  #Buscar pruebas de estacionariedad para matrices!!!
  #Revisar kpss.test para series individuales 
  #-------------------------------------------------------------------------------------------------------------
  
  #adf.test
  #k = trunc((length(ts_series[,i])-1)^(1/3)), Obtenido de la documentación de adf.test
  
  for (i in 1:length(info_name)){
    w = adf.test(ts_series[,i], alternative = c("stationary"),k = trunc((length(ts_series[,i])-1)^(1/3)))
    
    if(w$statistic<1 && w$p.value<0.05){
      r=toString(w$statistic)
      pv= toString(w$p.value)
      texto1= paste (info_name[i],"es estacionario con r =", r, " y p.value=",pv,sep = " ")
      a = print(texto1)
      
    }
    else{
      r=toString(w$statistic)
      pv= toString(w$p.value)
      texto1= paste (info_name[i],"es no-estacionario con r =", r, " y p.value=",pv,sep = " ")
      a= print(texto1)
    }
  }

  return(a)
}