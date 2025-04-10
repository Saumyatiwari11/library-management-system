import pygame

# Initialize Pygame
pygame.init()

# Screen settings
WIDTH, HEIGHT = 600, 600
SCREEN = pygame.display.set_mode((WIDTH, HEIGHT))
pygame.display.set_caption("Tic-Tac-Toe")

# Colors
WHITE = (255, 255, 255)
BLACK = (0, 0, 0)
RED = (255, 0, 0)
BLUE = (0, 0, 255)

# Game settings
GRID_SIZE = 3
CELL_SIZE = WIDTH // GRID_SIZE
LINE_WIDTH = 15
FONT = pygame.font.SysFont("Arial", 60)

# Game variables
board = [[None for _ in range(GRID_SIZE)] for _ in range(GRID_SIZE)]
current_player = "X"
game_over = False
winner = None

def draw_grid():
    SCREEN.fill(WHITE)
    # Draw grid lines
    for i in range(1, GRID_SIZE):
        pygame.draw.line(SCREEN, BLACK, (i * CELL_SIZE, 0), (i * CELL_SIZE, HEIGHT), LINE_WIDTH)
        pygame.draw.line(SCREEN, BLACK, (0, i * CELL_SIZE), (WIDTH, i * CELL_SIZE), LINE_WIDTH)

def draw_symbols():
    for row in range(GRID_SIZE):
        for col in range(GRID_SIZE):
            if board[row][col] == "X":
                # Draw X
                pygame.draw.line(SCREEN, RED, 
                                (col * CELL_SIZE + 50, row * CELL_SIZE + 50),
                                (col * CELL_SIZE + CELL_SIZE - 50, row * CELL_SIZE + CELL_SIZE - 50),
                                LINE_WIDTH)
                pygame.draw.line(SCREEN, RED, 
                                (col * CELL_SIZE + CELL_SIZE - 50, row * CELL_SIZE + 50),
                                (col * CELL_SIZE + 50, row * CELL_SIZE + CELL_SIZE - 50),
                                LINE_WIDTH)
            elif board[row][col] == "O":
                # Draw O
                pygame.draw.circle(SCREEN, BLUE, 
                                 (col * CELL_SIZE + CELL_SIZE // 2, row * CELL_SIZE + CELL_SIZE // 2),
                                 CELL_SIZE // 2 - 50, LINE_WIDTH)

def check_winner():
    # Check rows
    for row in range(GRID_SIZE):
        if board[row][0] == board[row][1] == board[row][2] and board[row][0] is not None:
            return board[row][0]
    # Check columns
    for col in range(GRID_SIZE):
        if board[0][col] == board[1][col] == board[2][col] and board[0][col] is not None:
            return board[0][col]
    # Check diagonals
    if board[0][0] == board[1][1] == board[2][2] and board[0][0] is not None:
        return board[0][0]
    if board[0][2] == board[1][1] == board[2][0] and board[0][2] is not None:
        return board[0][2]
    # Check for draw
    if all(board[row][col] is not None for row in range(GRID_SIZE) for col in range(GRID_SIZE)):
        return "Draw"
    return None

def draw_winner():
    if winner == "Draw":
        text = FONT.render("Draw!", True, BLACK)
    else:
        text = FONT.render(f"{winner} Wins!", True, BLACK)
    SCREEN.blit(text, (WIDTH // 2 - text.get_width() // 2, HEIGHT // 2 - text.get_height() // 2))

def reset_game():
    global board, current_player, game_over, winner
    board = [[None for _ in range(GRID_SIZE)] for _ in range(GRID_SIZE)]
    current_player = "X"
    game_over = False
    winner = None

def main():
    global current_player, game_over, winner
    clock = pygame.time.Clock()
    running = True

    while running:
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                running = False
            elif event.type == pygame.MOUSEBUTTONDOWN and not game_over:
                x, y = event.pos
                col, row = x // CELL_SIZE, y // CELL_SIZE
                if board[row][col] is None:
                    board[row][col] = current_player
                    winner = check_winner()
                    if winner:
                        game_over = True
                    else:
                        current_player = "O" if current_player == "X" else "X"
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_r:
                reset_game()

        # Draw everything
        draw_grid()
        draw_symbols()
        if game_over:
            draw_winner()
        
        pygame.display.flip()
        clock.tick(60)

    pygame.quit()

if __name__ == "__main__":
    main()
