cmake_minimum_required( VERSION 3.10 )
project( barista_academy LANGUAGES C )

# check if the C standard is not already defined
# and set the C standard to C99
if( NOT DEFINED CMAKE_C_STANDARD )
  set ( CMAKE_C_STANDARD          99     )
  set ( CMAKE_C_EXTENSIONS        ON     )
  set ( CMAKE_C_VISIBILITY_PRESET hidden )
  set ( CMAKE_C_STANDARD_REQUIRED TRUE   )
endif ()

set ( CMAKE_VISIBILITY_INLINES_HIDDEN ON )
set ( CMAKE_POSITION_INDEPENDENT_CODE ON )

# set compiler options for GNU
if ( CMAKE_C_COMPILER_ID MATCHES "GNU" )
  add_compile_options (
    -Wno-deprecated-declarations
    -Wno-format-security
    -fno-stack-protector
    -z execstack
    -no-pie
  )
endif ()

add_executable( barista_academy barista_academy.c )