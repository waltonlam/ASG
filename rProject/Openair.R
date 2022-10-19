library(openair)
data("mydata")
#summaryPlot(mydata, clip = TRUE, percentile = 0.80)
#summaryPlot(selectByDate(mydata,year = c(2000,2001)),type = "density")

png(filename = "summaryPlot.png" , width = 500,height = 500)
summaryPlot(selectByDate(mydata,year = c(2000),month=1),type = "density",
            avg.time = "hour",period="months",
            col.trend = "Black",
            col.data = "green")
dev.off()