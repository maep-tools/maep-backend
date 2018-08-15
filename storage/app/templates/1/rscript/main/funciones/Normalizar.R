#'Función que normaliza una matriz de acuerdo al máximo de sus columnas
#'@param MAtrixB: matriz que se quiere normalizar
#'@return una lista con una matriz con sus valores normalizados y un vector con los valores maximos
Normalizador <- function(MAtrixB) {
  nc=ncol(MAtrixB)
  nr=nrow(MAtrixB)
  maxM=matrix(nrow = 1,ncol = nc)
  for (i in 1:nc) {
    maxM[,i]=max(MAtrixB[,i])
    
  }
  NMAtrixB=matrix(nrow = nr,ncol = nc)
  for (i in 1:nc) {
    for (j in 1:nr) {
      NMAtrixB[j,i]=MAtrixB[j,i]/maxM[,i]
      
    }
    
  }
  result=list()
  result[[1]]=NMAtrixB
  result[[2]]=maxM
  return(result)
}