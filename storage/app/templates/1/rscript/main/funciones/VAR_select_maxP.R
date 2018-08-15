
VAR_select_maxP <- function(maxP,ts_series) {
  
  # AJUSTE MODELOS VAR
  #------------------------------------------------------------------------------------------------------------
  #Se evaluan los resultados de VAR(1:maxP), cada VAR(p) se almacena en el list "VARts_series", es importante
  #verificar cuál función VAR(p) se ha instalado, para este caso se usa VAR(p) del packete "MTS", si no se 
  #encuentra disponible es necesario ejecutar la instalación del paquete "MTS" o ejecutar "require(MTS)"
  #------------------------------------------------------------------------------------------------------------
  
  # Inicializa los arreglos por llenar
  VARts_series = list()
  myAIC = array(dim=c(maxP,1))
  myBIC = array(dim=c(maxP,1))
  myHQ = array(dim=c(maxP,1))
  VARp_resd = list()
  
  for(j in 1:maxP){ 
    VARts_series[[j]] = VAR(ts_series,p=j, output = T)
    myAIC[j,] = VAR(ts_series,p=j, output = FALSE )$aic
    myBIC[j,] = VAR(ts_series,p=j, output = FALSE )$bic
    myHQ[j,] = VAR(ts_series,p=j, output = FALSE )$hq
    VARp_resd[[j]]= VAR(ts_series,p=j,output = FALSE)$residuals #Extrae los residuales por VAR(p)
  }
  
  # Analisis de BIC, AIC y HQ
  bestAIC<-min(myAIC,na.rm = TRUE)
  bestBIC<-min(myBIC,na.rm = TRUE)
  bestHQ<-min(myHQ,na.rm = TRUE)
  
  indAIC <- which(myAIC==bestAIC)
  indBIC <- which(myBIC==bestBIC)
  indHQ <- which(myHQ==bestHQ)
  
  # Reportar el rezago seleccionado por criterio
  cat("Menor AIC", bestAIC,", obtenido para el rezago", indAIC,".", sep = " ")
  cat("Menor BIC", bestBIC,", obtenido para el rezago", indBIC,".", sep = " ")
  cat("Menor HQ", bestAIC,", obtenido para el rezago", indHQ,".", sep = " ")
  

  
  return(texto_final)

}
