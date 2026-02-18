"""
Screen Saver Application with Analog Clock and Date
Language: Python with tkinter
"""

import tkinter as tk
import math
from datetime import datetime

class ScreenSaverApp:
    def __init__(self, root):
        self.root = root
        self.root.title("Screen Saver - Analog Clock")
        self.root.configure(bg='black')
        self.root.attributes('-fullscreen', True)
        self.root.bind('<Escape>', lambda e: self.root.quit())
        self.root.bind('<Button-1>', lambda e: self.root.quit())
        
        # Create canvas for analog clock
        self.canvas = tk.Canvas(root, width=500, height=500, bg='black', highlightthickness=0)
        self.canvas.pack(pady=50)
        
        # Create date label
        self.date_label = tk.Label(root, font=('Arial', 36, 'bold'), fg='white', bg='black')
        self.date_label.pack(pady=30)
        
        # Create time label (digital)
        self.time_label = tk.Label(root, font=('Arial', 24), fg='gray', bg='black')
        self.time_label.pack(pady=10)
        
        # Draw clock face
        self.draw_clock_face()
        
        # Start updating
        self.update_clock()
    
    def draw_clock_face(self):
        """Draw the clock circle and numbers"""
        center_x, center_y = 250, 250
        radius = 200
        
        # Draw outer circle
        self.canvas.create_oval(center_x - radius, center_y - radius,
                                center_x + radius, center_y + radius,
                                outline='white', width=4)
        
        # Draw clock numbers
        for i in range(1, 13):
            angle = math.radians(90 - i * 30)
            x = center_x + (radius - 40) * math.cos(angle)
            y = center_y - (radius - 40) * math.sin(angle)
            self.canvas.create_text(x, y, text=str(i), fill='white', 
                                   font=('Arial', 20, 'bold'))
        
        # Draw center dot
        self.canvas.create_oval(center_x - 8, center_y - 8,
                               center_x + 8, center_y + 8,
                               fill='white', outline='white')
    
    def update_clock(self):
        """Update clock hands, date, and time"""
        now = datetime.now()
        
        # Clear previous hands
        self.canvas.delete('hand')
        
        center_x, center_y = 250, 250
        
        # Get current time
        hours = now.hour % 12
        minutes = now.minute
        seconds = now.second
        
        # Calculate angles (12 o'clock is at top, so we subtract from 90)
        hour_angle = math.radians(90 - (hours * 30 + minutes * 0.5))
        minute_angle = math.radians(90 - minutes * 6)
        second_angle = math.radians(90 - seconds * 6)
        
        # Draw hour hand
        hour_length = 100
        hour_x = center_x + hour_length * math.cos(hour_angle)
        hour_y = center_y - hour_length * math.sin(hour_angle)
        self.canvas.create_line(center_x, center_y, hour_x, hour_y,
                               fill='white', width=8, capstyle=tk.ROUND, tags='hand')
        
        # Draw minute hand
        minute_length = 150
        minute_x = center_x + minute_length * math.cos(minute_angle)
        minute_y = center_y - minute_length * math.sin(minute_angle)
        self.canvas.create_line(center_x, center_y, minute_x, minute_y,
                               fill='white', width=5, capstyle=tk.ROUND, tags='hand')
        
        # Draw second hand
        second_length = 170
        second_x = center_x + second_length * math.cos(second_angle)
        second_y = center_y - second_length * math.sin(second_angle)
        self.canvas.create_line(center_x, center_y, second_x, second_y,
                               fill='red', width=2, capstyle=tk.ROUND, tags='hand')
        
        # Update date
        date_str = now.strftime('%A, %B %d, %Y')
        self.date_label.config(text=date_str)
        
        # Update digital time
        time_str = now.strftime('%I:%M:%S %p')
        self.time_label.config(text=time_str)
        
        # Schedule next update (1000ms = 1 second)
        self.root.after(1000, self.update_clock)

def main():
    root = tk.Tk()
    app = ScreenSaverApp(root)
    root.mainloop()

if __name__ == '__main__':
    main()
