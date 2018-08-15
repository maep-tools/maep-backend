#'Simulador
#'@param Vari
#'@param simulaciones
#'@return intervalos de confianza
#'@return simulaciones
#'
Simu <- function(Vari,simulaciones,maxP,yearsPron) {
  Varis <- list()
  for (i in 1:simulaciones) {
    pr <- list()
    for (j in 1:maxP) {
      simulacion <- rnorm(yearsPron*12,mean = 0,sd = 1)
     pr[[j]] <- Vari[[j]]+simulacion
      
    }
    Varis[[i]] <-  assign(paste('x',sep = "",i),pr)
  }
  return(Varis)
  
}