Vestas117 <- function(vel) {
  potencia <- list()
  for (i in 1:length(vel)) {
    if (vel[i]<3.0) {
      potencia[i] <- 0
      
    }else if (vel[i]>11) {
      potencia[i] <- 3450
      
    }else {
      potencia[i] <- 54.636*vel[i]**2-389.62*vel[i]+758.94
    }
    }
    
  return(potencia)
  }
