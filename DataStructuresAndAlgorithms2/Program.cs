using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    internal class Program
    {
        static void Main(string[] args)
        {

            Sorting s = new Sorting();

            int[] A = { 3, 5, 8, 9, 6, 2 };

            Console.WriteLine("Origional Array:");
            s.display(A, 6);
         
            s.shellsort(A, 6);
            Console.WriteLine("Sorted Array: ");
            s.display(A, 6);
            Console.ReadKey();

           



        }
      
    }
}
