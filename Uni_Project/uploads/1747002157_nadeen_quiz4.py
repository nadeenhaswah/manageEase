import string
list1=[]
list2=[]
letter='a'
while letter!='S':
    letter=str(input("Enter a Letter .. Enter s to stop Entering : "))
    if letter.isupper():
        list1.append(letter)

    if letter.islower():
        list2.append(letter)
    
print(list1)
print(list2)

print(list2[-1]*3)

list1[1:3]=["Good" , "luck"]
print(list1)


print("%".join(list2))

M=[[ 2, 2,2 ],[2 , 2,2 ] ,[ 2,2 ,2 ]]  #initial values
def matrix(m,n):
    for i in range(0,n):
        for j in range(0,n):
            if i ==j or i<j:
                m[i][j]=0
            else:
                 m[i][j]=1

matrix(M,3)
print(M)
