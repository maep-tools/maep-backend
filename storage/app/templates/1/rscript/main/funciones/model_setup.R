#' @model_setup, result_list, List resultado de la importacion utilizando data_load, contiene matriz de datos, matriz de fechas, informacion series
#' 
model_setup <- function(m_series,m_dates,info_codes,testing_t,lag_max,seasonality,names,ffolder) {

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 1
# Set information as time series format

# Get beginning year and month 
month_i = as.numeric(format(m_dates[[1,1]], "%m"))
year_i = as.numeric(format(m_dates[[1,1]], "%Y"))

# Get final year and month 
month_f = as.numeric(format(m_dates[[length(m_dates[,1]),1]], "%m"))
year_f = as.numeric(format(m_dates[[length(m_dates[,1]),1]], "%Y"))

# Create time series
ts_seriesFull<- ts(m_series,start=c(year_i,month_i),end=c(year_f,month_f),frequency = 12,c("mts","ts","matrix"),names = info_codes)

# Set the requirement of testing time
if(testing_t == 1){
  year_it = year_i
  month_it = month_i
  availab_year = (year_f - year_i)+1
  train_period = ceiling(availab_year*0.80) #Number of years used to train, equal to 80% of full information 
  year_ft = year_i+train_period
  month_ft = 12 	
} else {
  # If testing_t = 0
  year_it = year_i
  month_it = month_i
  year_ft = year_f
  month_ft = month_f
}

# Create ts to train the model 
ts_series<-window(ts_seriesFull,c(year_it,month_it),c(year_ft,month_ft))

# Plot direct correlation, if d_correl = 1
if(d_correl == 1){
  correl= cor(ts_series)
  corrplot(correl,diag = F,type = "upper",method = "number",number.cex = 0.8, tl.cex =0.7,cl.cex=0.7, 
           order = "AOE",tl.col=1, add=FALSE,rect.col=1)
}

# Setup VAR model, auto setup with 
# TEST 1
var_auto_setup = vars::VARselect(ts_series, lag.max = lag_max, type = c("const"), season=seasonality, exogen = NULL)

# Gets maximum value of p from FPE criteria [4]
maxP = max(as.numeric(var_auto_setup$selection[1]),as.numeric(var_auto_setup$selection[2]),as.numeric(var_auto_setup$selection[3]),as.numeric(var_auto_setup$selection[4]))

# Test if maxP it's not nan value
# TEST 2
criteria = var_auto_setup$criteria[1,maxP]

# If maxP it's nan
if(is.na(criteria) == TRUE | criteria ==-Inf |criteria ==Inf && maxP<lag_max){
  maxP = maxP - 1
  if(var_auto_setup$criteria[1,maxP+1]<0){
    maxP=maxP-1
    }
}

# Test nexts value of p if maxP it's equal 1
# TEST 3
if(maxP == 1|2|3){
  if(var_auto_setup$criteria[4,maxP]>0|var_auto_setup$criteria[1,maxP]>0)
  {
  test_1 = max(which(var_auto_setup$criteria[4,]>0))
  test_2 = min(which(var_auto_setup$criteria[1,]== -Inf)) 
  test_3 = max(which(var_auto_setup$criteria[1,]>0))
  
  maxP = min(test_1,test_2,test_3,na.rm = FALSE)
  }
}

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 2
# Residuals assessment: get residuals for p = [60%*maxP, maxP]
minP=ceiling(0.6*maxP)

VARts_series <- list()
VARp_resd <-list()

# Calculates residuals using a set of velues p = [60%*maxP, maxP]
n_var = c(minP:maxP)     # Defines the p values to test

# Fits a VAR model for p in n_var
cont = 1
for(j in n_var){ 
  VARts_series[[cont]] = vars::VAR(ts_series,p=j,season=12)
  cont = cont+1
}

# Evaluates the number of residuals associated to p that overpasses the boundaries
# 1. ACF
# Initializing auxiliary variables
acf_residuals<- list()
over_limit <- list()
under_limit <- list()
outlier <- list()
aux <-0
best_p_acfres = c()
aux2 = c()
aux3 = list()

# Loop: i, moves between minP and maxP
#       j, moves between 1: number of series (plants)
#       k, moves between 1: number of series (plants)
  
for(i in 1:length(VARts_series)){
  
  # Count the number of residuals that overpasses the boundaries (lim_min -0.1, lim_max 0.1)
  for(j in 1:length(VARts_series[[i]]$varresult)){
        plot.new()
        acf_residuals[[j]]<- Acf(VARts_series[[i]]$varresult[[j]]$residuals, type = c("correlation"), main = paste(names[j],' VAR(',n_var[i],')',sep = ""),xlab = "Rezago", lag.max =12 , plot = FALSE)
        over_limit<- which(as.vector(acf_residuals[[j]]$acf)>0.10)
        under_limit<- which(as.vector(acf_residuals[[j]]$acf)<(-0.10))
        
        # Combines the vectors that assess upper and lower boundaries
        outlier[[j]]<- append(over_limit, under_limit)
  }
  
  for(k in 1:length(outlier)){
    
    # Adds up the number of times the group of series (plants) overpass the boundaries 
    aux = aux + length(outlier[[k]])
    
    # Build a vector 1Xnumber of series that holds en each position the nuber of times a serie overpases the boundaries 
    aux2[k]=length(outlier[[k]]) #vector 
    
    # Build a list() with n positions (n=length(n_var)), each position holds the vector saved in aux2
    aux3[[i]] = aux2
  }
  
  # Build a vector with n positiosn (n=length(n_var)), each position holds the number saved in aux
  best_p_acfres =append(best_p_acfres, (aux-length(outlier)))
  aux <-0
}

# 2. PACF
# Initializing auxiliary variables
pacf_residuals<- list()
over_limit <- list()
under_limit <- list()
outlier <- list()
aux <-0
best_p_pacfres = c()
aux2 = c()
aux3 = list()

# Loop: i, moves between minP and maxP
#       j, moves between 1: number of series (plants)
#       k, moves between 1: number of series (plants)

for(i in 1:length(VARts_series)){
  
  # Count the number of residuals that overpasses the boundaries (lim_min -0.1, lim_max 0.1)
  for(j in 1:length(VARts_series[[i]]$varresult)){
    pacf_residuals[[j]]<- Acf(VARts_series[[i]]$varresult[[j]]$residuals, type = c("partial"), main = paste(names[j],' VAR(',n_var[i],')',sep = ""),xlab = "Rezago", lag.max = 6, plot = FALSE)
    over_limit<- which(as.vector(pacf_residuals[[j]]$acf)>0.1)
    under_limit<- which(as.vector(pacf_residuals[[j]]$acf)<(-0.1))
    
    # Combines the vectors that assess upper and lower boundaries
    outlier[[j]]<- append(over_limit, under_limit)
  }
  
  for(k in 1:length(outlier)){
    
    # Adds up the number of times the group of series (plants) overpass the boundaries 
    aux = aux + length(outlier[[k]])
    
    # Build a vector 1Xnumber of series that holds en each position the nuber of times a serie overpases the boundaries 
    aux2[k]=length(outlier[[k]]) #vector 
    
    # Build a list() with n positions (n=length(n_var)), each position holds the vector saved in aux2
    aux3[[i]] = aux2
  }
  
  # Build a vector with n positiosn (n=length(n_var)), each position holds the number saved in aux

  best_p_pacfres =append(best_p_pacfres, aux)

  aux <-0
}

#::::::::::::::::::::::::::::::::::::::::::::::::: Section 3
# p selection 

p_acf=which.min(best_p_acfres)
p_pacf=which.min(best_p_pacfres)
p_model <- min(p_pacf,p_acf)


return(list(best_p_acfres,best_p_pacfres,n_var[[p_model]],VARts_series[[p_model]]))

# VARts_series[[p_model]] --- Main info given as input to forecast stage, tests it's performance


} #close function, don't delete 

