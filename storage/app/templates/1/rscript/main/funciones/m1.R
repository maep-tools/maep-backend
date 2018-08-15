#'
#'Funcion para calcular el indicador m1, que compara la media del periodo simulado y los datos reales
#'@param real son los datos reales
#'@param simu son los datos simulados
#'@return rm1 indicador m1
m1 <- function(real,simu) {
  mreal<-mean(real)
  msimu <- mean(simu)
  rm1<-msimu/mreal
  return(rm1)
}