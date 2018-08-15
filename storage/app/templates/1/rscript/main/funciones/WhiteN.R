#'Funcion para determinar si todas las series son ruido blanco
#'@param Residuales: residuales para comprar si son ruido blanco
#'@return Indicador: 0 si hay alguno que no es ruido blanco, 1 si todos son ruido blanco
WhiteN <- function(Residuales) {
  len=ncol(Residuales)
  prBox <- list()
  indicador=1
  for (i in 1:len) {
    prBox[[i]] <- Box.test(Residuales[,i],lag = i,type = "Lj")
    if (prBox[[i]]$p.value<0.05) {
      indicador=0
    }
  }
  return(indicador)
}