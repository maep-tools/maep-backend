#'
#'Funcion para calcular el indicador mdesv, que compara la desviacion estandar del periodo simulado y los datos reales
#'@param real son los datos reales
#'@param simu son los datos simulados
#'@return rmdesv indicador mdesv
mdesv <- function(real,simu) {
  mreal<-sd(real)
  msimu <- sd(simu)
  rmdesv<-msimu/mreal
  return(rmdesv)
}