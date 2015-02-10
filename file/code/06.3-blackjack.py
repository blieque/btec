#!/usr/bin/python3

# Blackjack

# Written by Blieque Mariguan
# as part of an IT BTEC.
# GPLv3 license applies

import sys
import platform
import os.path
import random

def load(path):

    try:
        if os.path.isfile(path):
            wins_file   = open(path, "r")
            wins_string = wins_file.read()
            wins_file.close()

            wins_split  = wins_string.split()
            wins_split  = [int(wins_split[0]), int(wins_split[1])]
            return wins_split

        else:
            return [0, 0]

    except IOError:
        print(highlight(1, "Failed to open win table."))

    except ValueError:
        print(highlight(1, "Corrupted wins table file.") +
              " Will overwrite (" +
              highlight(4, "press Ctrl+C to prevent") +
              ").")

def save(path, wins):

    try:
        wins_file = open(path, "w+")
        wins_file.write(str(wins[0]) + " " + str(wins[1]))
        wins_file.close()

    except IOError:
        print(highlight(1, "Failed to save win table."))
        return 1

    finally:
        return 0

def randomCard ():

    card = random.randint(0, 12)
    suit = random.randint(0, 3)

    return [card, suit]

def cardName(card):

    suit_prefix = "a" if (card[0] != 0 and card[0] != 7) else "an"
    card_name   = ["Ace",
                  "Two",
                  "Three",
                  "Four",
                  "Five",
                  "Six",
                  "Seven",
                  "Eight",
                  "Nine",
                  "Ten",
                  "Jack",
                  "Queen",
                  "King"][card[0]]
    suit_name   = ["Spades",
                  "Diamonds",
                  "Clubs",
                  "Hearts"][card[1]]

    return suit_prefix + " " + card_name + " of " + suit_name

def valueOf(cards, player, score_dealer = 0):

    to_add = 0

    for i in range(0, len(cards)):

        value = cards[i][0]

        if value == 0:
            if player == 0:
                # user has an ace
                choice = input("You were dealt an Ace; should it count as 1" +
                               " point or 11 points? [" +
                               highlight(4, "11") +
                               "/1] ")

                # keep asking until a valid answer is given
                while choice != "1" and choice != "11" and choice != "":
                    choice = input("You must enter either 11 or 1: ")

                # default to 11
                if choice == "":
                    choice = "11"
                    print("Defaulting to 11.")

                to_add += int(choice)

            else:
                # dealer has an ace
                # go for 11 if it would be beneficial
                if (score_dealer < 3 or
                  (score_dealer > 7 and score_dealer < 11)):

                    to_add += 11
                else:
                    to_add += 1

        elif value > 9:
            to_add += 10

        else:
            to_add += value + 1

    return to_add

def scoreTable(wins):

    wins_str  = [str(wins[0]), str(wins[1])]
    col_width = max([len(wins_str[0]), len(wins_str[1])])

    for i in range(0, 2):
        wins_str[i] = " " * (col_width - len(wins_str[i])) + wins_str[i]

    index_of_bigger  = 0 if wins[0] < wins[1] else 1
    index_of_smaller = (index_of_bigger - 1) * (index_of_bigger - 1)
    wins_str[index_of_bigger]  = highlight(5, wins_str[index_of_bigger])
    wins_str[index_of_smaller] = highlight(6, wins_str[index_of_smaller])

    if not windows:
        # unicode box-drawing
        print("Wins table:\n" +
              "┌────────┬─" + col_width * "─" + "─┐\n" +
              "│ Dealer │ " + wins_str[1]     + " │\n" +
              "├────────┼─" + col_width * "─" + "─┤\n" +
              "│ You    │ " + wins_str[0]     + " │\n" +
              "└────────┴─" + col_width * "─" + "─┘" )

    else:
        # dos box-drawing
        print("Wins table:\n" +
              "┌────────┬─" + col_width * "─" + "─┐\n" +
              "│ Dealer │ " + wins_str[1]     + " │\n" +
              "├────────┼─" + col_width * "─" + "─┤\n" +
              "│ You    │ " + wins_str[0]     + " │\n" +
              "└────────┴─" + col_width * "─" + "─┘" )

def highlight(colour, string):

    # no colours is the output is a file or similar
    # no colours for Windows as they aren't supported
    if sys.stdout.isatty() and not windows:

        colour_codes = ["\033[m",   # normal
                        "\033[31m", # red/error
                        "\033[32m", # green/correct
                        "\033[33m", # gold/warning
                        "\033[34m", # blue/emphasis
                        "\033[91m", # light red/error
                        "\033[92m", # light green/correct
                        "\033[93m", # light gold/warning
                        "\033[94m"] # light blue/emphasis
        return colour_codes[colour] + string + colour_codes[0]

    else:
        return string

def game(firstRound):

    # ========================== introduce the game ===========================

    welcome = ""

    if not windows:
        welcome  = "\n " + "─" * 33         # unicode box-drawing lines
        welcome += highlight(5, "  ╺╸ ")    # highlight bolder lines in the centre
        welcome += highlight(1, "╺╸")
        welcome += highlight(5, " ╺╸  ")
        welcome += "─" * 33 + "\n\n"

    else:
        # dos box-drawing
        welcome  = "\n " + "─" * 33 + "   ╣ ╬ ╠   " + "─" * 33 + "\n\n"

    if firstRound:                      # if the game has just been launched
        welcome += "Welcome to the table"
    else:                               # if the user is playing another round
        welcome += "Welcome back"
    print(welcome + ". Let's play.\n")   # send it out in one call

    # ================== initialise scores, deal two cards  ===================

    scores = [0, 0] # player, dealer

    hand_player = [randomCard(), randomCard()]      # deal four initial cards
    print("You have been dealt two cards; " +       # show the player theirs
          highlight(4, cardName(hand_player[0])) +
          " and " +
          highlight(4, cardName(hand_player[1])) +
          ".")

    # ============================= tot up scores =============================

    scores[0] += valueOf(hand_player, 0)    # pass hand array to valueOf, add
                                            # the result to scores variable
    print("This makes your score " +        # prettify and print score
          highlight(4, str(scores[0])) +
          ".")

    hand_dealer = [randomCard(), randomCard()]       # deal dealer's card
    scores[1]  += valueOf(hand_dealer, 1, scores[1]) # add card worth to score

    # ========================== natural win/draw =============================

    if scores[0] == 21:
        if scores[1] != 21: # only the player has 21
            print("You scored " +
                  highlight(4, "21") +
                  " instantly. Natural " +
                  highlight(2, "win") +
                  "!\n")
            wins[0]  += 1       # give win to player

        else:   # both player and dealer have 21
            print("You scored " +
                  highlight(4, "21") +
                  " instantly, as did the Dealer. It's a " +
                  highlight(8, "draw") +
                  ".\n")

        game_over = True    # prevent main game loop from running

    # ========================== main portion loop ============================

    # initialise some variables
    twist_count = [0, 0]
    stuck	= [False, False]
    game_over   = False
    dealer_cont = False
    
    # while neither player is stuck, and the game is not over
    while not (stuck[0] and stuck[1]) and not game_over:

        if not stuck[0]:    # if the player is in game

            action = input("\nDo you wish to " +
                           highlight(8, "stick") +
                           " or " +
                           highlight(8, "twist") +
                           "? [S/T]: ").upper()

            while action != "S" and action != "T":
                action = input("You must enter either S or T: ").upper()

            if action == "S":

                print("You " +
                      highlight(8, "stuck") +
                      ". You cannot make any more moves.\n" +
                      "Your final score is " +
                      highlight(4, str(scores[0])) +
                      ".\n")
                stuck[0] = True
                if not stuck[1]:
                    dealer_cont = True

            if action == "T":

                card_new   = randomCard();
                scores[0] += valueOf([card_new], 0)
                card_name  = cardName(card_new)
                print("\nYou were dealt " +
                      highlight(4, card_name) +
                      ", bringing your score to " +
                      highlight(4, str(scores[0])) +
                      ".")

                twist_count[0] += 1

                if scores[0] > 21:
                    print("You've gone " +
                          highlight(8, "bust") +
                          "! You " +
                          highlight(5, "lose") +
                          ".\n")
                    wins[1]  += 1
                    game_over = True

                elif twist_count[0] == 3:
                    print("You have twisted three times so you are " +
                          highlight(8, "stuck") +
                          ". Your score is final.\n")
                    stuck[0] = True
                    if not stuck[1]:
                        dealer_cont = True

        if not stuck[1] and not game_over:  # is dealer is in the game
            
            # dealer twist
            if scores[1] < 18:
                card_new   = randomCard();
                scores[1] += valueOf([card_new], 1, scores[1])

                twist_count[1] += 1

                if scores[1] > 21:
                    print("The Dealer decided to " +
                    highlight(8, "twist") +
                    " and went " +
                    highlight(8, "bust") +
                    "! " +
                    highlight(6, "You win") +
                    ".\n")
                    wins[0]  += 1
                    game_over = True

                elif twist_count[1] == 3:
                    print("The Dealer has twisted three times and is now " +
                          highlight(8, "stuck") +
                          ".")
                    stuck[1] = True

                else:
                    print("The Dealer decided to " +
                          highlight(8, "twist") +
                          ".")

            # dealer stick
            else:
                print("The Dealer decided to " +
                      highlight(8, "stick") +
                      ".")
                stuck[1] = True

    if game_over == False:

        if dealer_cont:
            print("")

        if scores[0] > scores[1]:
            print("You finished with a higher score than the Dealer. " +
                  highlight(6, "You win") +
                  ".\n")
            wins[0] += 1

        elif scores[0] < scores[1]:
            print("The Dealer finished with a higher score than you. You " +
                  highlight(5, "lose") +
                  ".\n")
            wins[1] += 1

        else:
            print("Both the Dealer and you have the same score. It's a " +
                  highlight(7, "draw") +
                  ".\n")

    scoreTable(wins)

    # ========================= ask to repeat game ============================

    play_again    = input("\nWould you like to play another round? [" +
                          highlight(4, "Y") +
                          "/n]: ")

    if play_again.upper() == "Y" or play_again == "":
        game(False)
    else:
        print("See you next time.\n")

windows = platform.system() == 'Windows'

if not windows:
    # unicode box-drawing
    print(highlight(4, "\n ╔" + "═" * 76 + "╗ \n ║" + " " * 16) +
          highlight(1, "┳━┓  ┳    ┏━━┓ ┏━━┓ ┳ ┏   ╺┳ ┏━━┓ ┏━━┓ ┳ ┏ ") +
          highlight(4, " " * 17 + "║ \n ║" + " " * 16) +
          highlight(1, "┣━┻┓ ┃    ┣━━┫ ┃    ┣━┻┓   ┃ ┣━━┫ ┃    ┣━┻┓") +
          highlight(4, " " * 17 + "║ \n ║" + " " * 16) +
          highlight(1, "┻━━┛ ┻━━┛ ┻  ┻ ┗━━┛ ┻  ┻ ┗━┛ ┻  ┻ ┗━━┛ ┻  ┻") +
          highlight(4, " " * 17 + "║ \n ╚" + "═" * 76 + "╝ "))

else:
    # dos box-drawing
    print("\n ╔" + "═" * 75 + "╗ \n ║" + " " * 15 +
          "┬─┐  ┬    ┌──┐ ┌──┐ ┬ ┌    ─┬  ┌──┐ ┌──┐ ┬ ┌ " +
          " " * 15 + "║ \n ║" + " " * 15 +
          "├─┴┐ │    ├──┤ │    ├─┴┐    │  ├──┤ │    ├─┴┐" +
          " " * 15 + "║ \n ║" + " " * 15 +
          "┴──┘ ┴──┘ ┴  ┴ └──┘ ┴  ┴ └──┘  ┴  ┴ └──┘ ┴  ┴" +
          " " * 15 + "║ \n ╚" + "═" * 75 + "╝ ")

path = "blackjack-wins.txt"
wins = load(path)

if wins != [0, 0]:
    print("")
    scoreTable(wins)

try:
    game(True)
    save(path, wins)

# ========================== pick up ^C, cleanly exit =========================

except KeyboardInterrupt:
    print(highlight(3, "\n\n" +
          "The Blackjack table collapses dramatically, scattering cards and" +
          "chips across\nthe scarlet carpet. The Dealer rushes to tidy the" +
          "casino floor, with customers\nlooking on in distaste. The game is" +
          "forced to close without a conclusion.\n"))
