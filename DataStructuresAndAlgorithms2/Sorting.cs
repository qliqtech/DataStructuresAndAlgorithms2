using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class Sorting
    {
        //{19,20,10,14,8}
        //int get position of lowest number in array
        //swap position

        public void selectionsort(int[] A, int n) {

            for (int i = 0; i < n - 1; i++) {

                int position = i;

                for (int j = i + 1; j < n; j++) {
                    if (A[j] < A[position])
                        position = j;
                }
                   
                
                int temp = A[i];
                
                A[i] = A[position];
               
                A[position] = temp;
            
            }

        
        }


        public void display(int[] A, int n) {

            for (int i = 0; i < n; i++)
                Console.WriteLine(A[i] + " ");
            Console.WriteLine();
        
        }


        // go through loop
        //get position of lowest item and swap



        public void selectionsort2(int[] A, int n) {

            for (int i = 0; i < n - 1; i++) {

                int position = i;

                for (int j = i + 1; j < n; j++)
                    if (A[j] < A[position])
                        j = position;


            }    
        
        }

    }
}
