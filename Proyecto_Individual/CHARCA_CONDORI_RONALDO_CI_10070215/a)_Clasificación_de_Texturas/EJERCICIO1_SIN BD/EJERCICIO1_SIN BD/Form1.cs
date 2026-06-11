using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace EJERCICIO1_SIN_BD
{
    public partial class Form1 : Form
    {
        Bitmap bmp;
        Bitmap bpm2;

        public Form1()
        {
            InitializeComponent();
        }

        private void btnCargar_Click(object sender, EventArgs e)
        {
            OpenFileDialog abrir = new OpenFileDialog();
            abrir.Filter = "Imagenes|*.jpg;*.jpeg;*.png;*.bmp";
            if (abrir.ShowDialog() == DialogResult.OK)
            {
                bmp = new Bitmap(abrir.FileName);
                pictureOriginal.Image = bmp;
            }
        }

    

        private void btnClasificar_Click(object sender, EventArgs e)
        {
            if (bmp == null)
            {
                MessageBox.Show("Primero cargue una imagen.");
                return;
            }

            bpm2 = new Bitmap(bmp.Width, bmp.Height);

            for (int y = 0; y < bmp.Height; y++)
            {
                for (int x = 0; x < bmp.Width; x++)
                {
                    Color pixel = bmp.GetPixel(x, y);

                    int r = pixel.R;
                    int g = pixel.G;
                    int b = pixel.B;

                    int promedio = (r + g + b) / 3;

                    Color nuevoColor;

                    if (g > r + 20 && g > b + 20)
                    {
                        nuevoColor = Color.FromArgb(34, 197, 94); // Cesped
                    }
                    else if (b > r + 25 && b > g + 15)
                    {
                        nuevoColor = Color.FromArgb(37, 99, 235); // Agua
                    }
                    else if (r > 90 && g > 45 && g < 160 && b < 120 && r > b)
                    {
                        nuevoColor = Color.FromArgb(146, 64, 14); // Tierra
                    }
                    else if (promedio < 90 && Math.Abs(r - g) < 30 && Math.Abs(g - b) < 30)
                    {
                        nuevoColor = Color.FromArgb(17, 24, 39); // Asfalto
                    }
                    else if (promedio >= 90 && Math.Abs(r - g) < 35 && Math.Abs(g - b) < 35)
                    {
                        nuevoColor = Color.FromArgb(156, 163, 175); // Cemento
                    }
                    else
                    {
                        nuevoColor = pixel;
                    }


                    bpm2.SetPixel(x, y, nuevoColor);
                }
            }

            pictureResultado.Image = bpm2;
        }

       
    }
}
