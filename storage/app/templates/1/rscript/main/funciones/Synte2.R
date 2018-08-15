Sint <- function(SerieA,SerieB,simulaciones) {
  
  fil <- nrow(SerieB)
  column <- ncol(SerieB)
  desvia <- matrix(0,nrow = 1,ncol = column)

  for (i in 1:column) {
    desvia[[i]] <- sd(SerieA[,i])
  }
  Simulaciones <- list()
  for (i in 1:simulaciones) {
    Simula <- matrix(0,ncol = column,nrow = fil)
    for (j in 1:column) {
      for (k in 1:fil) {
        aleatorio <- rnorm(1,mean = 0,sd = 1)
        Simula[k,j] <- SerieB[k,j]+desvia[[j]]*aleatorio
      }
      
    }
    Simulaciones[[i]] <- Simula
  }
  return(Simulaciones)
}

