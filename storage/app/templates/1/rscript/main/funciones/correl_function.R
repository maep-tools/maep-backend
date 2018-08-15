correl_function <- function(m_series,namesS,series_plot) {
  # Funcion calculo de correlacion entre series
  # Las correlaciones deben calcularse para promedios historicos mensuales y para valores historicos
  # generales de la serie.
  
  # 4. correlaciones historicas 
  series_full =as.matrix(m_series)
  colnames(series_full) <- namesS
  correlaciones_full <- cor(series_full)
  
  for (i in 1:length(namesS)) {
    for (j in 1:length(namesS)) {
      
      if (i==j) {
        correlaciones_full[j,i]=0
      }
    }
  }
  
  # 5. revisar complementariedad directa e inversa 
  correlaciones_negativas = array(dim=c(length(namesS),length(namesS)))
  correlaciones_positivas = array(dim=c(length(namesS),length(namesS)))
  
  for (i in 1:length(namesS)) {
    for (j in 1:length(namesS)) {
      if (correlaciones_full[j,i]>=0) {
        correlaciones_negativas[j,i]= NaN
        correlaciones_positivas[j,i]= correlaciones_full[j,i]
      } 
      else {
        correlaciones_negativas[j,i]= correlaciones_full[j,i]
        correlaciones_positivas[j,i]= NaN
      }
    }
  }
  
  # Convertit en matriz diagonal inferior 
  upperTriangle(correlaciones_positivas, diag=FALSE) <- 0
  upperTriangle(correlaciones_negativas, diag=FALSE) <- 0
  colnames(correlaciones_positivas) <- namesS
  colnames(correlaciones_negativas) <- namesS
  
  # 6. Indice de correlaciones (inveras o directas)
  vr_max_serie = array(dim=c(length(namesS),1))
  vr_min_serie = array(dim=c(length(namesS),1))
  # Calcula las correlaciones maximas al interior de las series 
  for (j in 1:length(namesS)) {
    vr_max_serie[j] = max(correlaciones_positivas[,j],na.rm = TRUE)
    vr_min_serie[j] = min(correlaciones_negativas[,j],na.rm = TRUE)
  }
  
  # mostrar matrices
  print(correlaciones_negativas)
  print(correlaciones_positivas)
  
  #------------------------------------------------------------------------------------------------------------
  # 7. Coeficientes de correlacion segun promedios mensuales historicos
  historic_avg = array(dim=c(12,length(namesS)))
  for (i in 1:ncol(series_full)) {
    matriz_prueba = matrix(series_full[,i], nrow = 12)
    a=rowMeans(matriz_prueba)
    historic_avg[,i]=a
  }
  colnames(historic_avg) = namesS
  correlaciones_avg <- cor(historic_avg)
  
  
  # 8. Creacion list con resultados
  correl_hist=list()
  correl_hist[[1]]=correlaciones_full
  correl_hist[[2]]=correlaciones_positivas
  correl_hist[[3]]=correlaciones_negativas
  
  correl_avg=list()
  correl_avg[[1]]=correlaciones_avg
  correl_avg[[2]]=historic_avg
  
  resultado_correl = list()
  resultado_correl [[1]] = correl_hist
  resultado_correl [[2]] = correl_avg

  #______________________________________________________________________________________
  # 9. Creacion plot
  a =length(info_name)
  b =2
  c = ceiling(a/b)
  c=4
  lim =1.2
  x =c("ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic")
  
  cant_plot = length(series_plot)
  
  name_leg ="A" #Esta linea se borra más adelante, solo es necesario inicializarla 
  i=1
  
  for (k in series_plot) {
    #x11(600,800)
    par(mfrow=c(c,b))
    for (i in 1:length(info_name)) {
      remove(name_leg)
      name_leg =as.character(namesS[k])
      name_leg=append(name_leg,as.character(namesS[i]),after = 0)
      
      y = historic_avg[,i]/(max(historic_avg[,i]))
      yl = historic_avg[,k]/(max(historic_avg[,k]))

       plot(y,type="l", ylim =c(0,lim), main = "Correlacion historica",xlab="mes",ylab= "Valores normalizados")
       lines(yl, col="red")
       legend("bottom",legend=name_leg,lty=c(1,1), col=c("black", "red"),horiz = TRUE)
    }
  }
  
  return(resultado_correl)

}

