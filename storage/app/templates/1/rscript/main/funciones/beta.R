g1<- function(alpha,beta,V1) {
  #Función 1 método Maxima Verosimilitud
  N <- length(V1)
  g1 <- digamma(alpha)-digamma(alpha+beta)-((1/N)*sum(log(V1/10)))
  return(g1)
}
g2 <- function(alpha,beta,V1) {
  #Función 2 método Maxima Verosimilitud
  N <- length(V1)
  g2 <- digamma(beta) - digamma(alpha+beta)-((1/N)*sum(log(1-V1/10)))   
  return(g2)
}
g1pa<-function(alpha,beta,V1){
  #Derivada función g1 respecto a alpha
  g1pa <- trigamma(alpha)-trigamma(alpha+beta)
  return(g1pa)
}
g1pb<-function(alpha,beta,V1){
  #Derivada función g1 respecto a beta
  g1pb <- -trigamma(alpha+beta)
  return(g1pb)
}

g2pa<-function(alpha,beta,V1){
  #Derivada función g2 respecto a alpha
  g2pa <- -trigamma(alpha+beta)
  return(g2pa)
}

g2pb<-function(alpha,beta,V1){
  #Derivada función g2 respecto a beta
  g2pb <- trigamma(beta)-trigamma(alpha+beta)
  return(g2pb)
}

betaAB<-function(V1,alpha1,beta1){
  #Función que determina los parámetros de beta para una serie de datos dada
  #-V1 una lista que contiene los valores de insolación diaria promedio mensual
  #-alpha1 valor inicial de alpha 
  #-beta1 valor inicial de beta
  
  #V1=np.asarray(V1)
  alpha <- alpha1
  beta <- beta1
  contador <- 0
  alphan <- 10
  betan <- 10
  for (i in 1:150) {
    alphan <- alpha- (((g2pb(alpha,beta,V1)*g1(alpha,beta,V1))-(g1pb(alpha,beta,V1)*g2(alpha,beta,V1)))*(1/(g1pa(alpha,beta,V1)*g2pb(alpha,beta,V1)-(g1pb(alpha,beta,V1)*g2pa(alpha,beta,V1)))))
    betan <- beta-(((-g2pa(alpha,beta,V1)*g1(alpha,beta,V1))+(g1pa(alpha,beta,V1)*g2(alpha,beta,V1)))*(1/(g1pa(alpha,beta,V1)*g2pb(alpha,beta,V1)-(g1pb(alpha,beta,V1)*g2pa(alpha,beta,V1)))))
    contador <- contador+1
    if ((abs(alpha-alphan)/alpha<0.005) & (abs(beta-betan)/beta<0.005)) {
      print("valor encontrado")
      return (c(alpha,beta))
      
    }
    alpha <- alphan
    beta <- betan
  }
  
}

