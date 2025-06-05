/// --------------------------------------------------------------------------------------
/// @file barista_academy.c
/// @brief HeadOfCoffee's CTF-style barista training challenges
///
/// This program implements a series of three interactive CTF challenges focused on
/// demonstrating common C vulnerabilities: buffer overflow, integer overflow, and
/// format string exploitation. It decrypts three flags using an XOR key, then guides
/// the user through each challenge in sequence:
///   1. ctf_challenge_one: overflow a 16-byte buffer to alter a coffee_strength variable.
///   2. ctf_challenge_two: trigger integer overflow by purchasing too many coffees.
///   3. ctf_final_challenge: leverage a format string vulnerability to reveal the final flag.
///
/// Each challenge prints hints, verifies user input, and displays the corresponding flag
/// when successfully exploited. Upon completion of all three challenges, an ASCII-art
/// portrait of the master barista is printed.
///
/// Global Variables:
///   - enc_flag1, enc_flag2, enc_flag3: Encrypted XORed byte arrays for each flag.
///   - flag1, flag2, flag3: Buffers to store the decrypted flags.
///   - coffee_challenge: Tracks current challenge state (0,1,2).
///   - actual_coffee_strength: Randomly generated target strength for challenge one.
///
/// @note
///   - XOR_KEY defines the single-byte XOR key used for decryption.
///   - FLAG_BUFFER_SIZE must be 30 to accommodate 29 encrypted bytes plus a null terminator.
///
/// @see
///   - decrypt(): Performs XOR decryption of encrypted flags.
///   - ctf_init_flags(): Decrypts all three flags at program start.
///   - ctf_main(): Main loop that prompts for flags or starts challenges.
///   - ctf_challenge_one(): Implements challenge 1 (buffer overflow).
///   - ctf_challenge_two(): Implements challenge 2 (integer overflow).
///   - ctf_final_challenge(): Implements challenge 3 (format string).
///   - print_master_barista(): Prints ASCII art upon completion.
///
/// @date June 4, 2025
/// --------------------------------------------------------------------------------------

#include <ctype.h>
#include <errno.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#define FLAG_BUFFER_SIZE 30

// encrypted flags (XORed byte arrays of your original flags)
static const unsigned char enc_flag1[FLAG_BUFFER_SIZE - 1] = {
  0x01, 0x16, 0x04, 0x39, 0x2f, 0x72, 0x21, 0x2a, 0x23, 0x1d, 
  0x2f, 0x3b, 0x31, 0x36, 0x71, 0x30, 0x2b, 0x27, 0x31, 0x1d, 
  0x30, 0x71, 0x34, 0x71, 0x76, 0x2e, 0x71, 0x26, 0x3f
};

static const unsigned char enc_flag2[FLAG_BUFFER_SIZE - 1] = {
  0x01, 0x16, 0x04, 0x39, 0x27, 0x31, 0x32, 0x30, 0x27, 0x31, 
  0x31, 0x2d, 0x1d, 0x71, 0x3a, 0x32, 0x30, 0x71, 0x31, 0x31, 
  0x1d, 0x31, 0x27, 0x21, 0x30, 0x27, 0x36, 0x31, 0x3f
};

static const unsigned char enc_flag3[FLAG_BUFFER_SIZE - 1] = {
  0x01, 0x16, 0x04, 0x39, 0x21, 0x23, 0x32, 0x32, 0x37, 0x21, 
  0x21, 0x73, 0x2c, 0x72, 0x1d, 0x21, 0x30, 0x76, 0x21, 0x29, 
  0x71, 0x30, 0x1d, 0x76, 0x2e, 0x71, 0x30, 0x36, 0x3f
};

static int coffee_challenge = 0;
static int actual_coffee_strength = 0;

#define XOR_KEY 0x42

static char flag1[FLAG_BUFFER_SIZE];
static char flag2[FLAG_BUFFER_SIZE];
static char flag3[FLAG_BUFFER_SIZE];

void decrypt(const unsigned char * input, char * output, size_t length);

void ctf_init_flags();
int ctf_main();
void ctf_challenge_one();
void ctf_challenge_two();
void ctf_final_challenge();

void decrypt(const unsigned char * input, char * output, size_t length) {
  for (size_t i = 0; i < length; i++) {
    output[i] = (char)(input[i] ^ XOR_KEY);
  }
  output[length] = '\0'; // Null-terminator
}

void ctf_init_flags()
{
  decrypt(enc_flag1, flag1, FLAG_BUFFER_SIZE - 1);
  decrypt(enc_flag2, flag2, FLAG_BUFFER_SIZE - 1);
  decrypt(enc_flag3, flag3, FLAG_BUFFER_SIZE - 1);
}

int ctf_main() {
  ctf_init_flags();
  printf("\n☕ === HeadOfCoffee's Barista Training Challenges === ☕\n");
  printf("Welcome to our exclusive barista academy!\n");
  printf("To unlock the secret portrait of our master barista, you need to prove your skills.\n");
  char buffer[FLAG_BUFFER_SIZE];
  // reset input buffer
  memset(buffer, '\0', FLAG_BUFFER_SIZE);
  printf("\nEnter secret coffee flag (if you dont have a flag press ENTER): ");
  if (!fgets(buffer, FLAG_BUFFER_SIZE, stdin)) {
    fprintf(stderr, "fgets failed while reading from stdin, ERROR: %s\n", strerror(errno));
    return -1;
  }
  buffer[strcspn(buffer, "\n")] = 0;
  if (strnlen(buffer, FLAG_BUFFER_SIZE) == 0 && coffee_challenge == 0) {
    srand((unsigned)time(NULL));
    char chr;
    do {
      chr = (char)(rand() % 127); // range includes printable ASCII
    } while (!isalpha((unsigned char)chr)); // ensure it's a-z or A-Z
    actual_coffee_strength = (int)chr;
    ctf_challenge_one();
  }
  if (strncmp(buffer, flag1, FLAG_BUFFER_SIZE) == 0 && coffee_challenge < 1) {
    coffee_challenge = 1;
  }
  if (strncmp(buffer, flag1, FLAG_BUFFER_SIZE) == 0 && coffee_challenge == 1) {
    printf("\n🎉 Excellent! First flag captured! (1/3)\n");
    printf("Challenge 1 (Buffer Overflow) completed!\n");
    printf("🔓 Challenge 2 unlocked: Look for integer overflow issues!\n\n");
    ctf_challenge_two();
  }
  if (strncmp(buffer, flag2, FLAG_BUFFER_SIZE) == 0 && coffee_challenge < 2) {
    coffee_challenge = 2;
  }
  if (strncmp(buffer, flag2, FLAG_BUFFER_SIZE) == 0 && coffee_challenge == 2) {
    printf("\n🎉 Excellent! Second flag captured! (2/3)\n");
    printf("Challenge 2 (Integer Overflow) completed!\n");
    printf("🔓 Challenge 3 unlocked: Look for format string issues!\n\n");
    ctf_final_challenge();
  }
  if (strncmp(buffer, flag3, FLAG_BUFFER_SIZE) == 0) {
    printf("\n🎉 Congratulations! Final flag captured! (3/3)\n");
    printf("Challenge 3 (Format String) completed!\n");
    return 0;
  }
  return -1;
}

/// # -> any ASCII char
/// X -> specific ASCII char revealed on overflow
/// solution: ############################X
/// result: CTF{m0cha_myst3ries_r3v34l3d}
void ctf_challenge_one()
{
  if (coffee_challenge != 0) {
    return;
  } 
  printf("\n🎯 Your mission: Modify the coffee strength to unlock the secret!\n");
  printf("💡 Hint: Sometimes too much input can overflow... like an overfilled coffee cup!\n\n");

  char coffee_order[16];
  int coffee_strength = 17;

  printf("Current coffee strength: %d\n", coffee_strength);

  while (1) {
    coffee_strength = 17;

    printf("Enter your favorite coffee blend: ");
    fflush(stdout);

    gets(coffee_order);

    printf("\nYour coffee order: %s\n", coffee_order);
    printf("Current coffee strength: %d\n", coffee_strength);
    fflush(stdout);

    if (coffee_strength == actual_coffee_strength) {
      printf("\nThe coffee strength has been perfectly adjusted!\n");
      printf("Here's your reward: %s\n", flag1);
      printf("Restart application and enter secret coffee flag!\n");
      fflush(stdout);
      break;
    }
    if (coffee_strength != 17) {
      printf("\n💡 Hint: Interesting... the coffee strength changed to %d\n", coffee_strength);
      printf("But that's not quite the right strength for our secret recipe.\n");
      printf("Try to get it to exactly %d for the perfect brew!\n\n", actual_coffee_strength);
      fflush(stdout);
    }
  }
}

/// solution: 2147483520
/// result: CTF{espresso_3xpr3ss_secrets}
void ctf_challenge_two()
{
  if (coffee_challenge != 1) {
    return;
  }
  int account_balance = 1100;
  int number_coffees = 0;
  int total_cost = 0;

  printf("You have %d coins to spend.\n", account_balance);
  printf("Coffees cost 900 coins each.\n");

  printf("\n🎯 Your Mission: Try to buy coffee and unlock the big prize!\n");
  printf("💡 Hint: Large purchases might overflow...\n\n");

  while (1) {
    printf("Enter the number of coffees to order (enter strings at your own risk): ");
    fflush(stdout);
    if (scanf("%d", &number_coffees) != 1) {
      printf("Invalid input. Exiting...\n");
      break;
    }
    if (number_coffees == 0) {
      printf("No coffee purchased. Continuing...\n");
      continue;
    }
    if (number_coffees < 0) {
      printf("Negative purchases are not allowed!\n");
      continue;
    }
    total_cost = 900 * number_coffees;
    printf("Calculated total cost: %d\n", total_cost);
    if (total_cost <= account_balance) {
      account_balance -= total_cost;
      printf("Purchase successful! New balance: %d\n", account_balance);
      if (account_balance >= 100000) {
        printf("Here's your reward: %s\n", flag2);
        printf("Restart application and enter secret coffee flag!\n");
        break;
      }
    }
    else {
      printf("Not enough balance for this purchase.\n");
    }
  }
}

/// https://robinlinus.github.io/endian/
/// https://www.rapidtables.com/convert/number/hex-to-ascii.html
/// solution: %llx,%llx,%llx,%llx,%llx,%llx,%llx,%llx,%llx,%llx,%llx,%llx,%llx
/// little endian output: 707061637b465443 635f306e31636375 345f72336b633472 7d7472336c
/// big endian output: 4354467B63617070 756363316E305F63 7234636B33725F34 6C3372747D
/// result: CTF{cappucc1n0_cr4ck3r_4l3rt}
void ctf_final_challenge()
{
  if (coffee_challenge != 2) {
    return;
  }
  printf("🎯 Your Mission: Print a special coffee order to reveal the secret flag!\n");
  printf("💡 Hint: Sometimes the order you place can change more than just the taste...\n");
  printf("💡 Hint: Try using special formatting to unlock hidden secrets in the menu!\n");
  printf("💡 Hint: No matter how big or small Indians are, everyone likes coffee!\n\n");

  char buffer[1024];
  memset(buffer, '\0', 1024);
  while (strncmp(buffer, "q", 1) != 0) {
    printf("Enter 'q' to exit challenge, if you think you have the key!\n");
    char coffee_secret1[64] = "Brazilian Dark Roast";
    char coffee_secret2[64];
    char coffee_secret3[64] = "Ethiopian Light Roast";
    strncpy(coffee_secret2, flag3, FLAG_BUFFER_SIZE);

    printf("\nWelcome to HeadOfCoffee's Secret Order Printer!\n");
    printf("Print your custom order below:\n");
    fflush(stdout);

    scanf("%1024s", buffer);

    printf("Processing order: ");
    printf(buffer);
    printf("\n\n");
  }
  printf("Thank you for your order. Goodbye!\n");
}

void print_master_barista() {
    const char *art[] = {
        "                                     ######                       ",
        "                                 %####%%%%%%%###                  ",
        "                               ##%%%%%%###%%%%%%#*                ",
        "                             %%%@@%%%##**+++#%%##***              ",
        "                            ##%@%%%%##*#+*#*##+==+*##             ",
        "                            %@@%%%%##****+*+++==-=+#*             ",
        "                           #%@@@%@%#***++====-::--=##*            ",
        "                           #%@#@%@##***+==----:-::-+##*           ",
        "                          *+%%#%@####*****+=---=+==-##+           ",
        "                          @#@%@%+*##%##++#%#=-++=---%**           ",
        "                          %%#%#*=**+=+#+++##=-+*##=-##            ",
        "                          #*##+++***+====*##=---==--+             ",
        "                          ##*+*+*###*++*%*#*=--==--:              ",
        "                          #%#=++##**#**#%%#**+==*+=-              ",
        "                           #=-=+#*#+=*#@##***+=++*=-              ",
        "                            +=+**#*+=+****===---=---              ",
        "                           =++=+****+*+**++------=-               ",
        "                          *++*+#**#**##**+=-------                ",
        "                        ++####%+*##*####****=+---=                ",
        "                     **++****%%%%####%#**+====--=+                ",
        "                  ++++++#=++***########+##*++++=***+##*===***      ",
        "               =+++++=++==*==+*++**#####%%##===*****%=++#==+=*-   ",
        "             =++****+++=====#===*+***##%%##+##+%##+%#*===+===*===+",
        "            -++*##%**#*+=====+=--=++##**#%##%#%#=+@%+=*+-=======+=-",
        "          *#=#*#**#*##++++*+--==-==+=++*+++*#*%#===*#=++========+=---",
        "          =#*+#*##*#***#*===+==-=-+==+===+++++%#=-==+%-==+-=======++--",
        "          +=*%#*###****+++++==--==-+=-====+++++%-+-===#-======-+=*+----",
        "          #++**##**###*+*+++==-----=+=--+=-=**+#-+=-===*===-=--=+=====--",
        "          =++**########**++======-#-=+=--=--=**+#=*=-=-=+-=-----=+++=+==--",
        "         -++++*######***++++====-=+*+-+=--=--=**==*+--=-=+----=--+++*+==----",
        "         =++++*######****++++===----=#=+=------+#-=#=-===-+--=======-+=====---",
        "         ++++**####*******++++===----=#=+=------=*==*--=-+===--=++==-+++====----",
        "         =+++***############*+====---=#+==-------+++%+=-=-==-----++=-**+++==-----:",
        "         +=+++**########***++++===-----#===-------+=+#==-==------=+=-#**+=======---:",
        "         ==++***##%%%%%###***+++===----**=--------=++#+-+=========+++====---------:::",
        "         ++++++**########*****+++===----+===-===-==-=+==--:::::::::---::::::::::::::::",
        "          +*+++***###%####*****+++====---=======+=+=--::::::::::::::::::::::::::::::::::",
        "          +++++****########****++==--------------::::::::::::::::::::::--:-:::::::::::::",
        "           +*+*+++*#########*+++====-----::::::::::----::==-----::::::::::::::-----::::::",
        "           ++*+++*+**#####****+++=====---:::::::::::-====*++===-----:::::::::::::::------",
        "           ++**++*%*+##********+++=====---:::::::::::----+#@@*+=====------::::::::---=+=-",
        "           +**##+##%#@%**********++==-----::::::::::::::----=+%@@+======------------=+",
        "           +*##%*+@@@@%%#*********+++==----:::::::::::::::::::---=##%=======-----",
        "           ++#+*%%%%%%%#%%%#+*********+=----:::::----:-::::::::::----=++",
        "          +++*#*%%%%%##%%%%%%#*++****#*+==--------------:::::::::::::-=",
        "          +*##*#%%%####%%%%%%%%%%*++****+=--=---------:--:-:::::::-=",
        "          +=##+######%%%%%%%%%%%##%%%+++++=+===----------------=+==+",
        "          =#%+#%%####%%%%%%%%%%#*#%%%%%%%#++++========---==*+===----=",
        "         +##**%%%##%%%%%%%%%%%###%%%%%%%%%%%%%%#%%%###***%========---",
        "         *#*+#%%%%%%%%%%%%########%%%############***+===++-=====-----",
        "         **+*#%%%%%%%%%%#############**#****#***++++++====+=======---",
        "         ***#%#%%%%%%%%##########*****##*+++**++++++==++=-#=-==--=---",
        "         ***###%%%%#####################*+=++++++++=====+===--====---",
        "         **###%%%############**#######***++++++++++++====+=*---=====-",
        "         **######%##########*******##*****++++++++++++====++========--",
        "          *####%#%##########****#**********+++++++++++=====+========--",
        "         *#####%%%%########******#***********++++++++++====+*-+=====-",
        "           *###%#%%%########******************+++++++++====+@========",
        "          %%%#################*****************++++++++====+%=======-",
        "         *#%@@@##%%###############**************+++++++++===#===-===-",
        "         +*#%@@@@@%%%%%%%%############***********+++++++++=+*====-==",
        "        ****##%@@@@@#@%##################***********+++++++===+++==-",
        "        **#######*@*#@@@@@@@@#######*****************++++++++=++++*+",
        "       #*#*********#+*+**#%@@@@@@@@@@@###*#*#*#*******++++++=+=+=%#-",
        "       *############+*##+++++++#%%@@@@@@##@@#*##**#***+*+*%%@%%=-:::-",
        "       *###########*%+#%#=#=+++++=+++*#@**@@@@@@@@@%%%++==+%+---------",
        "      **%%############*#%%+#===++======+%=**#%#@@@@@*+*++%#*----------",
        "      *%%##########%####*%@@==-=========+====++==*++==-*=-------------"
    };
    size_t length = sizeof(art) / sizeof(art[0]);
    for (size_t i = 0; i < length; i++) {
        printf("%s\n", art[i]);
    }
    printf("\nPsst... here’s a secret from the master barista: sometimes it’s not about coffee, it’s about drinking Mezzo Mix — the official fuel of champions!\n\n");
}

int main()
{
  if (ctf_main() == 0) {
    printf("🏆 CONGRATULATIONS! YOU'VE COMPLETED ALL CHALLENGES! 🏆\n");
    printf("You are a true CTF champion!\n");
    printf("All vulnerabilities successfully exploited!\n\n");

    print_master_barista();
  }
  return 0;
}
