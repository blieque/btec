#!/usr/bin/python3
# the above line is a shebang for Linux systems, making the script executable

# import the required libraries
import random

# using a class essentially allows for grouped, named variables
class output_colours:
	normal		= "\033[m"
	incorrect	= "\033[91m"
	correct		= "\033[92m"
	warning		= "\033[93m"
	emphasis	= "\033[94m"

# ask the user for their name, and say hello
# the title() method makes sure the first letter is upper-case
name	= input(output_colours.emphasis + "\nWhat's your name? " + output_colours.normal).title()
print("Hello, " + name + "!")

def run_quiz():

	# initialise a variable to count correct answers
	count_correct	= 0

	# execute the question section five times
	# although i isn't needed, it does improve the user experience ("question i of 5")
	for i in range(1, 6):

		# generate two random numbers in an array, and find their product
		numbers	= [
			random.randint(1,21),
			random.randint(1,21)
		]
		product	= numbers[0] * numbers[1]


		# introduce question
		print("\nQuestion " + output_colours.emphasis + str(i) + output_colours.normal + " of 5:")

		# the question has yet to be answered with a valid answer (correctness irrelevant)
		answered	= False

		while not answered:

			# an "x" is used rather than a "*" as not everyone is familiar with the latter
			answer	= input("What is " + str(numbers[0]) + " x " + str(numbers[1]) + "? ")

			# try to convert answer to an integer
			try:
				answer	= int(answer)

			# if they didn't give a valid numeric answer
			except ValueError:
				print(output_colours.warning +
					"That answer doesn't seem to be a whole number." +
					"Please give your answer as digits, such as \"45\"." +
					output_colours.normal)

			# if everything goes well
			else:
				answered	= True

		# compare the user's answer with the real product
		# if the answer was right:
		if answer == product:
			# congratulate and increase count_correct by one
			print(output_colours.correct + "Correct!" + output_colours.normal + " Well done!")
			count_correct  += 1

		# if the answer was wrong:
		else:
			# tell them the correct answer
			print(output_colours.incorrect +
				"Sorry; that wasn't correct." +
				output_colours.normal +
				" The answer is actually " +
				output_colours.emphasis +
				str(product) +
				output_colours.normal)

	# initialise comment variable
	# this holds a string with a comment about the user's score
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
	print("\nWell, " +
		name +
		", you scored " +
		output_colours.emphasis +
		str(count_correct) +
		output_colours.normal +
		"/5. " +
		comment)

	# ask the user if they would like to run the quiz again
	try_again	= input("Would you like to have another go? [" +
		output_colours.correct +
		"Y" +
		output_colours.normal +
		"/" +
		output_colours.incorrect +
		"N" +
		output_colours.normal +
		"] ")
	if try_again.upper() == "Y":
		run_quiz()

# run the quiz
# up until this point it has merely been defined in a function, and must be called
run_quiz()

# bid farewell before quitting
print(output_colours.emphasis + "\nBye!" + output_colours.normal)
