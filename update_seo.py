import os
import re

filepaths = [
    "index.php", "sim-card.php", "money-transfer.php", "admission-processing.php",
    "consultation.php", "test-prep.php", "guide-me.php", "country.php", "bank-account.php",
    "services.php", "contact.php", "education-loan.php", "events.php", "universities.php",
    "part-time-jobs.php", "accommodation.php", "branch.php", "courses.php", "visa-processing.php",
    "university-selection.php", "gallery.php"
]

simple_container_start = '<div class="container" style="padding-top: 2rem; padding-bottom: 1rem;"><h1 class="section__title" style="text-align:center; margin:0; font-size: 2.2rem;">'
simple_container_end = '</h1></div>'

for fname in filepaths:
    fpath = os.path.join(r"c:\xampp\htdocs\Bluestone Overseas", fname)
    if not os.path.exists(fpath):
        continue
        
    with open(fpath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Replace the background section with a simple container
    pattern = r'<section style="background:linear-gradient\(135deg,#f8fafc,#f0fdf4\); padding: 3rem 0; border-bottom: 1px solid #e2e8f0;\"><div class=\"container\"><h1 class=\"section__title\" style=\"text-align:center; margin:0; font-size: 2\.2rem;\">(.*?)</h1></div></section>'
    
    def repl(match):
        inner_text = match.group(1)
        return f'{simple_container_start}{inner_text}{simple_container_end}'
        
    new_content = re.sub(pattern, repl, content)
    
    with open(fpath, 'w', encoding='utf-8') as f:
        f.write(new_content)

print("Removed background from H1 tags and made it simple.")
