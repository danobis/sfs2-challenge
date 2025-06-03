#include <stdio.h>
#include <string.h>

int main() {
  char buf[1024];
  char coffee_secret1[64] = "Brazilian Dark Roast";
  char flag[64] = "CTF{b3an_bl3nd_5h0w5_fl4g}";
  char coffee_secret2[64] = "Ethiopian Light Roast";

  printf("Welcome to HeadOfCoffee's Secret Order Printer!\n");
  printf("Place your custom order below:\n");
  fflush(stdout);

  scanf("%1024s", buf);

  printf("Processing order: ");
  printf(buf); // Vulnerable line
  printf("\n");

  printf("Thank you for your order. Goodbye!\n");
  return 0;
}
