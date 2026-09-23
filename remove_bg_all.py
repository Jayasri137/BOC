
from PIL import Image
import os

def remove_background(input_path, output_path, tolerance=40):
    img = Image.open(input_path).convert('RGBA')
    width, height = img.size
    pixels = img.load()

    bg_color = pixels[0, 0]
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

files = [
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/australia_3d_landmark_1788771095911.png', 'assets/images/3d_australia_new.png'),
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/china_3d_landmark_1788771115367.png', 'assets/images/3d_china_new.png'),
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/singapore_3d_landmark_1788771129051.png', 'assets/images/3d_singapore_new.png'),
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/russia_3d_landmark_1788771141828.png', 'assets/images/3d_russia_new.png'),
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/spain_3d_landmark_1788771160266.png', 'assets/images/3d_spain_new.png'),
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/canada_3d_landmark_1788771179041.png', 'assets/images/3d_canada_new.png'),
    ('C:/Users/dell_/.gemini/antigravity-ide/brain/c99f48f3-e2ed-4a55-bc47-6e3dc095d410/germany_3d_landmark_1788771194985.png', 'assets/images/3d_germany_new.png')
]

for in_file, out_file in files:
    print(f'Processing {out_file}...')
    remove_background(in_file, out_file, 40)
print('All backgrounds removed and files saved.')

