library(igraph) #importing the library
#we are expecting a matrix in the file matrix.csv
N <- as.matrix(read.csv("matrix.csv" , sep = ";" , header = FALSE), directed = TRUE)
g <- graph.adjacency(N,mode="directed",weighted = TRUE)
png(filename = "graph.png" , width = 500,height = 500)
plot(g,g.vertex.color="lightblue")
dev.off()