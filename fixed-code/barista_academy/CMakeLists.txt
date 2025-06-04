cmake_minimum_required( VERSION 3.10 )
project( barista_academy LANGUAGES C )

# check if the C standard is not already defined
# and set the C standard to C17
if( NOT DEFINED CMAKE_C_STANDARD )
  set ( CMAKE_C_STANDARD          17     )
  set ( CMAKE_C_EXTENSIONS        ON     )
  set ( CMAKE_C_VISIBILITY_PRESET hidden )
  set ( CMAKE_C_STANDARD_REQUIRED TRUE   )
endif ()

set ( CMAKE_VISIBILITY_INLINES_HIDDEN ON )
set ( CMAKE_POSITION_INDEPENDENT_CODE ON )

# set compiler options for GNU
if ( CMAKE_C_COMPILER_ID MATCHES "GNU" )
  add_compile_options (
    # enable all warnings
	-Wall              # enable all standard warnings
	-Wconversion       # warn for implicit type conversions that may change a value
	-Wconversion-null  # warn when a constant integer zero is cast to a pointer type
	-Wdouble-promotion # warn if a float is implicitly promoted to double
	-Wextra            # enable extra warning messages that are not enabled by -Wall
	-Wpedantic         # issue all the warnings demanded by strict ISO C and ISO C++
	-Wundef            # warn if an undefined identifier is evaluated in an #if directive
  )
endif ()

add_executable( barista_academy barista_academy.c )