/*
            Copyright Oliver Kowalke 2009.
   Distributed under the Boost Software License, Version 1.0.
      (See accompanying file LICENSE_1_0.txt or copy at
          http://www.boost.org/LICENSE_1_0.txt)
*/

/*******************************************************
 *                                                     *
 *  -------------------------------------------------  *
 *  |  0  |  1  |  2  |  3  |  4  |  5  |  6  |  7  |  *
 *  -------------------------------------------------  *
 *  |  0  |  4  |  8  |  12 |  16 |  20 |  24 |  28 |  *
 *  -------------------------------------------------  *
 *  |    F14    |    F15    |    F16    |    F17    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  8  |  9  |  10 |  11 |  12 |  13 |  14 |  15 |  *
 *  -------------------------------------------------  *
 *  |  32 |  36 |  40 |  44 |  48 |  52 |  56 |  60 |  *
 *  -------------------------------------------------  *
 *  |    F18    |    F19    |    F20    |    F21    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  16 |  17 |  18 |  19 |  20 |  21 |  22 |  23 |  *
 *  -------------------------------------------------  *
 *  |  64 |  68 |  72 |  76 |  80 |  84 |  88 |  92 |  *
 *  -------------------------------------------------  *
 *  |    F22    |    F23    |    F24    |    F25    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  24 |  25 |  26 |  27 |  28 |  29 |  30 |  31 |  *
 *  -------------------------------------------------  *
 *  |  96 | 100 | 104 | 108 | 112 | 116 | 120 | 124 |  *
 *  -------------------------------------------------  *
 *  |    F26    |    F27    |    F28    |    F29    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  32 |  33 |  34 |  35 |  36 |  37 |  38 |  39 |  *
 *  -------------------------------------------------  *
 *  | 128 | 132 | 136 | 140 | 144 | 148 | 152 | 156 |  *
 *  -------------------------------------------------  *
 *  |    F30    |    F31    |   fpscr   |    TOC    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  40 |  41 |  42 |  43 |  44 |  45 |  46 |  47 |  *
 *  -------------------------------------------------  *
 *  | 160 | 164 | 168 | 172 | 176 | 180 | 184 | 188 |  *
 *  -------------------------------------------------  *
 *  |    R14    |    R15    |     R16   |    R17    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  48 |  49 |  50 |  51 |  52 |  53 |  54 |  55 |  *
 *  -------------------------------------------------  *
 *  | 192 | 196 | 200 | 204 | 208 | 212 | 216 | 220 |  *
 *  -------------------------------------------------  *
 *  |    R18    |    R19    |    R20    |    R21    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  56 |  57 |  58 |  59 |  60 |  61 |  62 |  63 |  *
 *  -------------------------------------------------  *
 *  | 224 | 228 | 232 | 236 | 240 | 244 | 248 | 252 |  *
 *  -------------------------------------------------  *
 *  |    R22    |    R23    |    R24    |    R25    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  64 |  65 |  66 |  67 |  68 |  69 |  70 |  71 |  *
 *  -------------------------------------------------  *
 *  | 256 | 260 | 264 | 268 | 272 | 276 | 280 | 284 |  *
 *  -------------------------------------------------  *
 *  |    R26    |    R27    |    R28    |    R29    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  72 |  73 |  74 |  75 |  76 |  77 |  78 |  79 |  *
 *  -------------------------------------------------  *
 *  | 288 | 292 | 296 | 300 | 304 | 308 | 312 | 316 |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |    R30    |    R31    |     CR    |     LR    |  *
 *  -------------------------------------------------  *
 *  -------------------------------------------------  *
 *  |  80 |  81 |                                   |  *
 *  -------------------------------------------------  *
 *  | 320 | 324 |                                   |  *
 *  -------------------------------------------------  *
 *  |    PC     |                                   |  *
 *  -------------------------------------------------  *
 *                                                     *
 *******************************************************/

.globl make_fcontext_v1
#if _CALL_ELF == 2
	.text
	.align 2
make_fcontext_v1:
	addis	%r2, %r12, .TOC.-make_fcontext_v1@ha
	addi	%r2, %r2, .TOC.-make_fcontext_v1@l
	.localentry make_fcontext_v1, . - make_fcontext_v1
#else
	.section ".opd","aw"
	.align 3
make_fcontext_v1:
# ifdef _CALL_LINUX
	.quad	.L.make_fcontext_v1,.TOC.@tocbase,0
	.type	make_fcontext_v1,@function
	.text
	.align 2
.L.make_fcontext_v1:
# else
	.hidden	.make_fcontext_v1
	.globl	.make_fcontext_v1
	.quad	.make_fcontext_v1,.TOC.@tocbase,0
	.size	make_fcontext_v1,24
	.type	.make_fcontext_v1,@function
	.text
	.align 2
.make_fcontext_v1:
# endif
#endif
    # save return address into R6
    mflr  %r6

    # first arg of make_fcontext_v1() == top address of context-stack
    # shift address in R3 to lower 16 byte boundary
    clrrdi  %r3, %r3, 4

    # reserve space for context-data on context-stack
    # including 64 byte of linkage + parameter area (R1 % 16 == 0)
    subi  %r3, %r3, 392

    # third arg of make_fcontext_v1() == address of context-function
    # entry point (ELFv2) or descriptor (ELFv1)
#if _CALL_ELF == 2
    # save address of context-function entry point
    std  %r5, 320(%r3)
#else
    # save address of context-function entry point
    ld   %r4, 0(%r5)
    std  %r4, 320(%r3)
    # save TOC of context-function
    ld   %r4, 8(%r5)
    std  %r4, 152(%r3)
#endif

    # load LR
    mflr  %r0
    # jump to label 1
    bl  1f
1:
    # load LR into R4
    mflr  %r4
    # compute abs address of label finish
    addi  %r4, %r4, finish - 1b
    # restore LR
    mtlr  %r0
    # save address of finish as return-address for context-function
    # will be entered after context-function returns
    std  %r4, 312(%r3)

    # restore return address from R6
    mtlr  %r6

    blr  # return pointer to context-data

finish:
    # save return address into R0
    mflr  %r0
    # save return address on stack, set up stack frame
    std  %r0, 8(%r1)
    # allocate stack space, R1 % 16 == 0
    stdu  %r1, -32(%r1)

    # exit code is zero
    li  %r3, 0
    # exit application
    bl  _exit
    nop
#if _CALL_ELF == 2
	.size make_fcontext_v1, .-make_fcontext_v1
#else
# ifdef _CALL_LINUX
	.size .make_fcontext_v1, .-.L.make_fcontext_v1
# else
	.size .make_fcontext_v1, .-.make_fcontext_v1
# endif
#endif

#ifndef __NetBSD__
/* Mark that we don't need executable stack.  */
.section .note.GNU-stack,"",%progbits
#endif
