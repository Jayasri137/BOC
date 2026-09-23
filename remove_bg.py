
from PIL import Image

def remove_background(input_path, output_path, tolerance=15):
    img = Image.open(input_path).convert('RGBA')
    width, height = img.size
    pixels = img.load()

    # Get the background color from the top-left corner
    bg_color = pixels[0, 0]

    # Simple flood fill to find contiguous background pixels
    stack = [(0, 0), (width - 1, 0), (0, height - 1), (width - 1, height - 1)]
    visited = set()

    def is_similar(c1, c2, tol):
        return abs(c1[0] - c2[0]) <= tol and abs(c1[1] - c2[1]) <= tol and abs(c1[2] - c2[2]) <= tol

    while stack:
        x, y = stack.pop()
        if (x, y) in visited:
            continue
        visited.add((x, y))

        if x < 0 or x >= width or y < 0 or y >= height:
            continue

        if is_similar(pixels[x, y], bg_color, tolerance):
            pixels[x, y] = (255, 255, 255, 0)
            
            if x + 1 < width and (x + 1, y) not in visited: stack.append((x + 1, y))
            if x - 1 >= 0 and (x - 1, y) not in visited: stack.append((x - 1, y))
            if y + 1 < height and (x, y + 1) not in visited: stack.append((x, y + 1))
            if y - 1 >= 0 and (x, y - 1) not in visited: stack.append((x, y - 1))

    img.save(output_path, 'PNG')

remove_background('assets/images/3d_dubai_new.png', 'assets/images/3d_dubai_new.png', 40)
remove_background('assets/images/3d_uk_new.png', 'assets/images/3d_uk_new.png', 40)
remove_background('assets/images/3d_japan_new.png', 'assets/images/3d_japan_new.png', 40)
print('Backgrounds removed.')

