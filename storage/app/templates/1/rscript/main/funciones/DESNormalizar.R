#'Función que desnormaliza una matriz de acuerdo al máximo de sus columnas
#'@param NMAtrixB: matriz que se quiere normalizar
#'@param maxM vector con los valores maximos utilizados para desnormalizar
#'@return una matriz con sus valores desnormalizados 
DESNormalizador <- function(NMAtrixB,maxM) {
  nc=ncol(NMAtrixB)
  nr=nrow(NMAtrixB)

  MAtrixB=matrix(nrow = nr,ncol = nc)
  for (i in 1:nc) {
    for (j in 1:nr) {
      MAtrixB[j,i]=NMAtrixB[j,i]*maxM[,i]
      
    }
    
  }
  return(MAtrixB)
}