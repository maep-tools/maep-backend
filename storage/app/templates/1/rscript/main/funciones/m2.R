#'
#'Funcion para calcular el indicador m2, que compara el parámetro de escala
#'de Weibull
#'@param real son los datos reales
#'@param simu son los datos simulados
#'@return rm2 indicador m2
m2 <- function(real,simu) {
  areal <- WeibullAB(real,5,mean(real))[2]
  asimu <- WeibullAB(simu,5,mean(simu))[2]
  rm2<-asimu/areal
  return(rm2)
}