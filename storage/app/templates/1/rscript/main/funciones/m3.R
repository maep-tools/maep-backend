#'
#'Funcion para calcular el indicador m2, que compara el parámetro de forma
#'de Weibull
#'@param real son los datos reales
#'@param simu son los datos simulados
#'@return rm3 indicador m2
m3 <- function(real,simu) {
  areal <- WeibullAB(real,4,mean(real))[1]
  asimu <- WeibullAB(simu,4,mean(simu))[1]
  rm3<-asimu/areal
  return(rm3)
}