synthetic_simulaton <- function(m_series, names, info_model,scenarios,forecast_periods,location){


#::::::::::::::::::::::::::::::::::::::::::::::::: Section 1

# forecast using forecas tool from Package 'forecast' https://cran.r-project.org/web/packages/forecast/forecast.pdf

# forecast input 1 : VAR model for slected p -> SETUP_model[[4]] #info_model = SETUP_model[[4]]
# forecast input 2 : forecasted periods 

VARp_forecast <- forecast(info_model,h=(forecast_periods*12))


# Extract as matrix forecasted values only for the selected value of p
for(i in 1:1){
  forecast_mean <- VARp_forecast$forecast[[i]]$mean
  for(j in 2:length(VARp_forecast$forecast)){
    forecast_mean <-cbind(forecast_mean,VARp_forecast$forecast[[j]]$mean)
  }
}
colnames(forecast_mean) <-names

# Assess model regard historic datasets 
for(i in 1:ncol(forecast_mean)){
  aux<-c()  
  aux <- forecast_mean[,i] < (min(m_series[,i])*.9)
  aux <-which(aux,TRUE)
  
  if(length(aux)>=1){
    for(j in 1:length(aux))
    qtl<- abs(rnorm(1,0,0.01))
    aux1 <- quantile(m_series[,i],qtl)
    forecast_mean[,i][aux[j]]<-aux1
  }
}

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 2

# Synthetic generation of scenrios around best estimator generated (forecast_mean)

# setA, original data (m_series)
# setB, forecastes data (forecast_mean)
# scenarios, numer of series to be simulated 

# Variable initialization
ROWS <-nrow(forecast_mean)
COLUMNS <-ncol(forecast_mean)
DesvEST <- matrix(0,nrow = 1,ncol = COLUMNS)

# Standar deviation original dataset
for(i in 1:COLUMNS){
  DesvEST[i]<-sd(m_series[,i])
}

# Synthetic generation throuhg normally distributed error additon 
synthetic_data <-list()

for(i in 1:scenarios){
  aux <-matrix(0, ncol = COLUMNS,nrow = ROWS)
  
  for(j in 1:COLUMNS){
    k=1
  
      while(k<ROWS+1){
      random <- rnorm(1,mean = 0,sd = 1)
      aux[k,j] <-forecast_mean[k,j]+DesvEST[j]*random
      
      if(aux[k,j]>0){
        k=k+1
      }
      }
    synthetic_data[[i]]<-aux
  }
}

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 3

# Output matrix generator 

# 1. Inflow
Inflow_matrix = matrix(0,nrow = (12*forecast_periods*scenarios),ncol = (length(names)+2))
aux =1

for(i in 1:(12*forecast_periods)){
  for(j in 1:length(synthetic_data)){
    Inflow_matrix[aux,1]=i
    Inflow_matrix[aux,2]=j
    Inflow_matrix[aux,3:ncol(Inflow_matrix)]=synthetic_data[[j]][i,]
    aux=aux+1
  }
}

Inflow_names <- c('Stage','Scenario')
Inflow_names <- append( Inflow_names,names)
colnames(Inflow_matrix)<-Inflow_names


# 2. Spread apart forecasted scenarios between Hydro and RE sources

# Initialize 
Inflow_hydro <-Inflow_matrix[,1:2]
Inflow_wind <-Inflow_matrix[,1:2]
hydro_names <-c('Stage','Scenario')
wind_names <-c('Stage','Scenario')

for(i in 1:length(location)){
  
  if (location[i]==1){
    n = i+2
    Inflow_hydro = cbind(Inflow_hydro,Inflow_matrix[,n])
    hydro_names = append(hydro_names,names[i])
  }
  if (location[i]== 2|location[i]==3){
    m = i+2
    Inflow_wind= cbind(Inflow_wind,Inflow_matrix[,m])
    wind_names = append(wind_names,names[i])
  }
}

colnames(Inflow_hydro)<-hydro_names
colnames(Inflow_wind)<-wind_names


return(list(Inflow = Inflow_hydro, InflowWind = Inflow_wind, full_forecast=Inflow_matrix))

}
  