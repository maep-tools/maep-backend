#' @SynteticGeneration, Descripcion 
#function ()
#{

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 1
# forecast

source(paste(ffolder,'Synte2.R',sep=''))
#Input
# numbers of years to forecast, called "forecast_time"
# number of syntetic series desired, called "scenarios"

# This function uses as imput the fitted p-model and the required forecast time 
forecast_avg <- forecast(SETUP_model[[4]], h= (forecast_time*12))

# Takes the info of every dataset/plant in the analisys o a p-model 
frcstd_values <-forecast_avg$mean[1][[1]]
for (i in 2:length(names)){
  frcstd_values <- cbind(frcstd_values,forecast_avg$mean[i][[1]])
}

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 2
# Syntetic generation 

# Initializing variables 
AvgForecast <- list()
SdForecast <- list()

# Data generation 
DataGen <- Sint(m_series,frcstd_values,scenarios)

# Historical sd 
for(i in 1:ncol(m_series)){
  SdForecast[[i]] <- sd(m_series[,i])
}

# Assessment of Sybtetic datasets 
SynteticData <- list()
aux <- c()

for(i in 1:ncol(m_series)){
  aux = DataGen[[1]][,i]  
  for(j in 2:scenarios){
    aux = cbind(aux,DataGen[[j]][,i])
  }
  SynteticData [[i]]<-aux
}

PrintTable <- SynteticData[[1]] 
for(i in 2:length(names)){
  PrintTable <-cbind(PrintTable,SynteticData[[i]])
}

library(xlsx)
write.xlsx(PrintTable, "C:/Users/ab.pedraza1391/SharePoint/Acuerdo 2 - Productos/Modelo subastas/series subastas/mydata3.xlsx")


return(list(SynteticData=PrintTable))


#formato para exportar series según Jose Morillo

#}



