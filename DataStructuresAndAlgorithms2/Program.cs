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

            Search search = new Search();

            int[] myNum = { 10, 20, 40, 50 };



            Console.WriteLine("result: " + search.binarysearch(4, myNum, 40));

            Console.ReadLine();

        }


    }
}
