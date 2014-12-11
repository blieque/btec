#!/usr/bin/python3
# the above line is a shebang for Linux systems, making the script executable

# import the required libraries
import random

# initialise a variable to cound correct answers
count_correct	= 0

# ask the user for their name, and say hello
# the title() method makes sure the first letter is uppercase
name	= input("\nWhat's your name? ").title()
print("Hello, " + name + "!")

def run_quiz():

	# execute the question section five times
	# although i isn't needed, it does improve the user experience ("question i of 5")
	for i in range(1, 6):

		# generate two random numbers in an array, and find their product
		numbers	= [
			random.randint(1,20),
			random.randint(1,20)
		]
		product	= numbers[0] * numbers[1]

		# ask question, get answer
		# an "x" is used rather than a "*" as not everyone is familiar with the latter
		print("\nQuestion " + str(i) + " of 5:")
		answer	= input("What is " + str(numbers[0]) + " x " + str(numbers[1]) + "? ")

		# compare the user's answer with the real product
		# if the answer was right:
		if int(answer) == product:
			# congratulate and increase count_correct by one
			print("Correct! Well done!")
			count_correct  += 1

		# if the answer was wrong:
		else:
			# tell them the correct answer
			print("Sorry; that was't correct. The answer is actually " + str(product))

	# initialise comment variable
	# this holds a string with a comment on the 
	comment		= ""
	if count_correct == 5:
		comment	= "Perfect!"
	elif count_correct == 4:
		comment	= "Almost 100%. Good job!"
	elif count_correct == 3:
		comment	= "Not bad!"
	elif count_correct == 2:
		comment	= "Could be worse."
	elif count_correct == 1:
		comment	= "At least you got one right!"
	elif count_correct == 0:
		comment	= "Better luck next time."

	# tell the user what score they reached
	print("\nWell, " + name + ", you scored " + str(count_correct) + "/5. " + comment)
			
	# ask the user if they would like to run the quiz again
	tryagain	= input("Would you like to have another go? [Y/N] ")
	if tryagain.upper() == "Y":
		run_quiz()

# run the quiz
# up until this point it has merely been defined in a function, and must be called
run_quiz()

# bid farewell before quitting
print("\nBye!")
